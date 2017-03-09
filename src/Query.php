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

use byrokrat\amount\Amount;

/**
 * Filter and iterate over collections of accounting objects
 */
class Query implements Interfaces\Queryable, \IteratorAggregate, \Countable
{
    /**
     * @var callable Internal factory for creating the query iterator
     */
    private $iteratorFactory;

    /**
     * @var \Closure Registerad macros
     */
    private static $macros = [];

    /**
     * Load macro
     */
    public static function macro(string $name, \Closure $macro)
    {
        self::$macros[$name] = $macro;
    }

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
                    yield from $item->select();
                }
            }
        };
    }

    /**
     * Execute macro if defined
     *
     * @throws Exception\LogicException If macro $name is not defined
     */
    public function __call(string $name, array $args)
    {
        if (isset(self::$macros[$name])) {
            return self::$macros[$name]->call($this, ...$args);
        }

        throw new Exception\LogicException("Call to undefined method ".__CLASS__."::$name()");
    }

    /**
     * Filter that returns only Account objects
     */
    public function accounts(): Query
    {
        return $this->filterType(Account::CLASS);
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
    public function asArray(): array
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
    public function asContainer(): Container
    {
        return new Container(...$this->asArray());
    }

    /**
     * Summarize transactions matched by query
     */
    public function asSummary(): TransactionSummary
    {
        $summary = new TransactionSummary;

        foreach ($this->transactions()->getIterator() as $transaction) {
            $summary->addToSummary($transaction);
        }

        return $summary;
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
            ->getFirst();
    }

    /**
     * Count the items currently in iterator
     *
     * Implements the Countable interface
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
     * Create a new query with those items that pass a truth test definied in $filter
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
     * Create a new query with those items thet are an instance of $type
     */
    public function filterType(string $type): Query
    {
        return $this->filter(function ($item) use ($type) {
            return $item instanceof $type;
        });
    }

    /**
     * Find account object with id
     *
     * @throws Exception\RuntimeException If account does not exist
     */
    public function getAccount(string $accountId): Account
    {
        return $this->accounts()->getDimension($accountId);
    }

    /**
     * Find first Dimension with id $dimensionId
     *
     * @throws Exception\RuntimeException If dimension does not exist
     */
    public function getDimension(string $dimensionId): Dimension
    {
        $dimension = $this->filter(function ($item) use ($dimensionId) {
            return $item instanceof Dimension && $item->getId() == $dimensionId;
        })->getFirst();

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
    public function getFirst()
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
                    yield from $item->select();
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
     * Implements the Queryable interface
     */
    public function select(): Query
    {
        return $this;
    }

    /**
     * Filter that returns only Transaction objects
     */
    public function transactions(): Query
    {
        return $this->filterType(Transaction::CLASS);
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
        return $this->filterType(Verification::CLASS);
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
     * Filter those objects that contain a specific account number
     */
    public function whereAccount(string $accountId): Query
    {
        return $this->where(function ($item) use ($accountId) {
            return $item instanceof Account && $item->getId() == $accountId;
        });
    }

    /**
     * Filter those objects whose amount matches $amount when compared using $comp
     */
    public function whereAmount(string $comp, Amount $amount): Query
    {
        return $this->where(function ($item) use ($comp, $amount) {
            if ($item instanceof Verification) {
                return $item->getMagnitude()->$comp($amount);
            }

            if ($item instanceof Transaction) {
                return $item->getAmount()->$comp($amount);
            }

            return false;
        });
    }

    /**
     * Filter those objects that contains a specific amount
     */
    public function whereAmountEquals(Amount $amount): Query
    {
        return $this->whereAmount('equals', $amount);
    }

    /**
     * Filter those objects that contains an amount greater than $amount
     */
    public function whereAmountIsGreaterThan(Amount $amount): Query
    {
        return $this->whereAmount('isGreaterThan', $amount);
    }

    /**
     * Filter those objects that contains an amount less than $amount
     */
    public function whereAmountIsLessThan(Amount $amount): Query
    {
        return $this->whereAmount('isLessThan', $amount);
    }

    /**
     * Filter attributable objects with a specific attribute set
     *
     * @param  string $name  Case-insensitive name of attribute
     * @param  mixed  $value If specified attribute must be set to value for filter to pass
     */
    public function whereAttribute(string $name, $value = null): Query
    {
        return $this->filter(function ($item) use ($name, $value) {
            return $item instanceof Interfaces\Attributable
                && $item->hasAttribute($name)
                && (is_null($value) || $item->getAttribute($name) == $value);
        });
    }

    /**
     * Filter those objects that contain a description matching $regexp
     */
    public function whereDescription(string $regexp): Query
    {
        return $this->where(function ($item) use ($regexp) {
            return $item instanceof Interfaces\Describable
                && preg_match($regexp, $item->getDescription());
        });
    }

    /**
     * Validate that data can be traversed using a foreach loop
     *
     * @param  mixed $data
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
