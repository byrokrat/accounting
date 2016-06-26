<?php
/**
 * This file is part of byrokrat/accounting.
 *
 * byrokrat/accounting is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * byrokrat/accounting is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

/**
 * Filter and iterate over collections of accounting objects
 */
class Query implements \IteratorAggregate, \Countable
{
    /**
     * @var callable Internal factory for creating the query iterator
     */
    private $iteratorFactory;

    /**
     * Load data to query
     *
     * @param array|\Traversable $items Any number of collections of items
     */
    public function __construct(...$collections)
    {
        $this->iteratorFactory = function () use ($collections) {
            foreach ($collections as $index => $items) {
                if (!is_array($items) && !$items instanceof \Traversable) {
                    throw new Exception\InvalidArgumentException(
                        "Unexpected query source $index (" . gettype($items) . '), expecting array or Traversable.'
                    );
                }

                yield from $this->generateQueryableContent($items);
            }
        };
    }

    /**
     * Filter that returns only Account objects
     */
    public function accounts(): self
    {
        return $this->filter(function ($item) {
            return $item instanceof Account;
        });
    }

    /**
     * Filter that returns only Amount objects
     */
    public function amounts(): self
    {
        return $this->filter(function ($item) {
            return $item instanceof Amount;
        });
    }

    /**
     * Check if query contains a given value
     *
     * @param mixed $value The value to search for
     */
    public function contains($value): bool
    {
        return !!$this
            ->filter(function ($item) use ($value) {
                return $item === $value;
            })
            ->first();
    }

    /**
     * Count the items currently in iterator
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * Immediately execute callback for all items in query
     *
     * @param callable $callback Executed for all items matching query
     */
    public function each(callable $callback): self
    {
        return $this->lazyEach($callback)->exec();
    }

    /**
     * Execute loaded filters and maps by iterating over all items
     */
    public function exec(): self
    {
        foreach ($this->getIterator() as $void) {
        }

        return $this;
    }

    /**
     * Filter the query set by keeping those items that pass a truth test definied in $filter
     *
     * @param callable $filter Takes one argument and returnes a boolean
     */
    public function filter(callable $filter): self
    {
        $outerIterator = ($this->iteratorFactory)();

        $this->iteratorFactory = function () use ($outerIterator, $filter) {
            foreach ($outerIterator as $item) {
                if ($filter($item)) {
                    yield $item;
                }
            }
        };

        return $this;
    }

    /**
     * Get the first item matched by query
     *
     * @return mixed The first item in query, null if no item is found
     */
    public function first()
    {
        foreach ($this->getIterator() as $item) {
            return $item;
        }

        return null;
    }

    /**
     * Get iterator for items filtered by query
     */
    public function getIterator(): \Generator
    {
        return ($this->iteratorFactory)();
    }

    /**
     * Check if query does not match any item
     */
    public function isEmpty(): bool
    {
        foreach ($this->getIterator() as $item) {
            return false;
        }

        return true;
    }

    /**
     * Lazily execute callback for all items in query
     *
     * @param callable $callback Executed for all items matching query
     */
    public function lazyEach(callable $callback): self
    {
        return $this->filter(function ($item) use ($callback) {
            $callback($item);

            return true;
        });
    }

    /**
     * Lazily execute callback if filter matches
     *
     * @param  callable $filter   Takes an $item and returns a boolean
     * @param  callable $callback Called for all items matching $filter
     */
    public function lazyOn(callable $filter, callable $callback): self
    {
        return $this->filter(function ($item) use ($filter, $callback) {
            if ($filter($item)) {
                $callback($item);
            }

            return true;
        });
    }

    /**
     * Transform items in query by passing each items to $callback
     *
     * @param callable $callback Takes one argument and returnes a modified version
     */
    public function map(callable $callback): self
    {
        $outerIterator = ($this->iteratorFactory)();

        $this->iteratorFactory = function () use ($outerIterator, $callback) {
            foreach ($outerIterator as $item) {
                yield $callback($item);
            }
        };

        return $this;
    }

    /**
     * Get all items matched by query
     *
     * As the result set is constructed by multiple generators the probability
     * of having multiple items with the same native key is high, which in turn
     * triggers unexpected behaviour in iterator_to_array(). For that reason
     * this method should be used instead of iterator_to_array.
     *
     * @return array The collection of items currently in query
     */
    public function toArray(): array
    {
        $items = [];

        foreach ($this->getIterator() as $item) {
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Filter that returns only Queryable objects
     */
    public function queryables(): self
    {
        return $this->filter(function ($item) {
            return $item instanceof Queryable;
        });
    }

    /**
     * Reduces items in query to a single value
     *
     * @param  callable $callback Takes two values, the result of the previous iteration and the current item
     * @param  mixed    $initial  Optional initial value of the first iteration
     * @return mixed    The final value of the reduction
     */
    public function reduce(callable $callback, $initial = null)
    {
        $this->each(function ($item) use ($callback, &$initial) {
            $initial = $callback($initial, $item);
        });

        return $initial;
    }

    /**
     * Filter that returns only Transaction objects
     */
    public function transactions(): self
    {
        return $this->filter(function ($item) {
            return $item instanceof Transaction;
        });
    }

    /**
     * Filter unique items in query
     */
    public function unique(): self
    {
        $uniqueItems = [];

        return $this->filter(function ($item) use (&$uniqueItems) {
            if (in_array($item, $uniqueItems, true)) {
                return false;
            }

            $uniqueItems[] = $item;

            return true;
        });
    }

    /**
     * Filter that returns only Verification objects
     */
    public function verifications(): self
    {
        return $this->filter(function ($item) {
            return $item instanceof Verification;
        });
    }

    /**
     * Filter those objects that are or contain a child that are matching $filter
     *
     * @param callable $filter Takes one argument and returnes a boolean
     */
    public function where(callable $filter): self
    {
        return $this->filter(function ($item) use ($filter) {
            return !(new Query([$item]))->filter($filter)->isEmpty();
        });
    }

    /**
     * Filter those objects that are not and does not contain a child matching $filter
     *
     * @param callable $filter Takes one argument and returnes a boolean
     */
    public function whereNot(callable $filter): self
    {
        return $this->filter(function ($item) use ($filter) {
            return (new Query([$item]))->filter($filter)->isEmpty();
        });
    }

    /**
     * Recursively iterate over items and quearyables
     *
     * @param array|\Traversable $items The items to iterate over
     */
    private function generateQueryableContent($items): \Generator
    {
        foreach ($items as $item) {
            yield $item;
            if ($item instanceof Queryable) {
                yield from $this->generateQueryableContent($item->getQueryableContent());
            }
        }
    }
}
