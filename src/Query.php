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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016-17 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\accounting\Interfaces\Queryable;
use byrokrat\amount\Amount;

/**
 * Filter and iterate over collections of accounting objects
 */
class Query implements Queryable, \IteratorAggregate, \Countable
{
    /**
     * @var callable Internal factory for creating the query iterator
     */
    private $iteratorFactory;

    /**
     * Load data to query
     *
     * @param array|\Traversable|\Closure $data Items to query. If items is a
     *     Closure it is expected to take no parameters and return a generator.
     */
    public function __construct($data = [])
    {
        if ($data instanceof \Closure) {
            $this->iteratorFactory = $data;
            return;
        }

        $this->validateTraversability($data);

        $this->iteratorFactory = function () use ($data) {
            foreach ($data as $item) {
                yield $item;
                if ($item instanceof Interfaces\Queryable) {
                    yield from $item->query();
                }
            }
        };
    }

    /**
     * Filter that returns only Account objects
     */
    public function accounts(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Account;
        });
    }

    /**
     * Filter that returns only Amount objects
     */
    public function amounts(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Amount;
        });
    }

    /**
     * Filter that returns only Attributable objects
     */
    public function attributables(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Interfaces\Attributable;
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
     * Filter that returns only Dateable objects
     */
    public function dateables(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Interfaces\Dateable;
        });
    }

    /**
     * Filter that returns only Describable objects
     */
    public function describables(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Interfaces\Describable;
        });
    }

    /**
     * Filter that returns only Dimension objects
     */
    public function dimensions(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Dimension;
        });
    }

    /**
     * Immediately execute callback for all items in query
     *
     * @param callable $callback Executed for all items matching query
     */
    public function each(callable $callback): Query
    {
        return $this->lazyEach($callback)->exec();
    }

    /**
     * Execute loaded filters and maps by iterating over all items
     */
    public function exec(): Query
    {
        foreach ($this->getIterator() as $void) {
        }

        return $this;
    }

    /**
     * Create a new querywith those items that pass a truth test definied in $filter
     *
     * @param callable $filter Takes one argument and returnes a boolean
     */
    public function filter(callable $filter): Query
    {
        $outerIterator = ($this->iteratorFactory)();

        return new Query(function () use ($outerIterator, $filter) {
            foreach ($outerIterator as $item) {
                if ($filter($item)) {
                    yield $item;
                }
            }
        });
    }

    /**
     * Get the first item in query matching filter
     *
     * @param  callable $filter Takes one argument and returnes a boolean
     * @return mixed    The first matching item, null if no item is found
     */
    public function find(callable $filter)
    {
        return $this->filter($filter)->first();
    }

    /**
     * Find account object with id
     *
     * @throws Exception\RuntimeException If account does not exist
     */
    public function findAccount(string $accountId): Account
    {
        return $this->accounts()->findDimension($accountId);
    }

    /**
     * Find first Dimension with id $dimensionId
     *
     * @throws Exception\RuntimeException If dimension does not exist
     */
    public function findDimension(string $dimensionId): Dimension
    {
        $dimension = $this->dimensions()->find(function ($dimension) use ($dimensionId) {
            return $dimension->getId() == $dimensionId;
        });

        if ($dimension) {
            return $dimension;
        }

        throw new Exception\RuntimeException("Dimension $dimensionId does not exist");
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
    public function lazyEach(callable $callback): Query
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
    public function lazyOn(callable $filter, callable $callback): Query
    {
        return $this->filter(function ($item) use ($filter, $callback) {
            if ($filter($item)) {
                $callback($item);
            }

            return true;
        });
    }

    /**
     * Create a new Query including addtional data
     *
     * @param  array|\Traversable $data Items to include
     * @throws Exception\LogicException if $data is not traversable
     */
    public function load($data): Query
    {
        $this->validateTraversability($data);
        $outerIterator = ($this->iteratorFactory)();

        return new Query(function () use ($outerIterator, $data) {
            yield from $outerIterator;
            foreach ($data as $item) {
                yield $item;
                if ($item instanceof Interfaces\Queryable) {
                    yield from $item->query();
                }
            }
        });
    }

    /**
     * Create a new Query that transform items by passing each item to $callback
     *
     * @param callable $callback Takes one argument and returnes a modified version
     */
    public function map(callable $callback): Query
    {
        $outerIterator = ($this->iteratorFactory)();

        return new Query(function () use ($outerIterator, $callback) {
            foreach ($outerIterator as $item) {
                yield $callback($item);
            }
        });
    }

    /**
     * Filter that returns only Signable objects
     */
    public function signables(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Interfaces\Signable;
        });
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
     * Get all items matched by query wrapped in a container
     */
    public function toContainer(): Container
    {
        return new Container(...$this->toArray());
    }

    /**
     * Implements the Queryable interface
     */
    public function query(): Query
    {
        return $this;
    }

    /**
     * Filter that returns only Queryable objects
     */
    public function queryables(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Interfaces\Queryable;
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
    public function transactions(): Query
    {
        return $this->filter(function ($item) {
            return $item instanceof Transaction;
        });
    }

    /**
     * Filter unique items in query
     */
    public function unique(): Query
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
    public function verifications(): Query
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
    public function where(callable $filter): Query
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
    public function whereNot(callable $filter): Query
    {
        return $this->filter(function ($item) use ($filter) {
            return (new Query([$item]))->filter($filter)->isEmpty();
        });
    }

    /**
     * Filter attributable objects with a specific attribute set
     *
     * @param  string $name  Case-insensitive name of attribute
     * @param  mixed  $value If specified attribute must be set to value for filter to pass
     */
    public function withAttribute(string $name, $value = null): Query
    {
        return $this->attributables()->filter(function ($item) use ($name, $value) {
            return $item->hasAttribute($name) && (is_null($value) || $item->getAttribute($name) == $value);
        });
    }

    /**
     * Filter those objects that contain a specific account number
     */
    public function withAccount(string $accountId): Query
    {
        return $this->where(function ($item) use ($accountId) {
            return $item instanceof Account && $item->getId() == $accountId;
        });
    }

    /**
     * Validate that data can be traversed using a foreach loop
     *
     * @param  mixed $data
     * @return void
     * @throws Exception\LogicException if $data is not traversable
     */
    private function validateTraversability($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new Exception\LogicException(
                "Unexpected query source (" . gettype($data) . '), expecting array or Traversable.'
            );
        }
    }
}
