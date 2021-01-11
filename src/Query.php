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
 * Copyright 2016-21 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidDimensionException;
use byrokrat\accounting\Exception\InvalidVerificationException;
use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\amount\Amount;

/**
 * Filter and iterate over collections of accounting objects
 *
 * @implements \IteratorAggregate<AccountingObjectInterface>
 */
class Query implements \IteratorAggregate, \Countable
{
    /** @var \Closure Internal factory for creating the query iterator */
    private \Closure $iteratorFactory;

    /** @var array<\Closure> Registerad macros */
    private static array $macros = [];

    /**
     * Register macro
     */
    public static function macro(string $name, \Closure $macro): void
    {
        if (method_exists(__CLASS__, $name) || isset(self::$macros[$name])) {
            throw new RuntimeException("Cannot create macro, $name() already exist.");
        }

        self::$macros[$name] = $macro;
    }

    /**
     * @param array<AccountingObjectInterface> $items
     */
    public function __construct(array $items = [])
    {
        $this->iteratorFactory = function () use ($items) {
            foreach ($items as $item) {
                if (!$item instanceof AccountingObjectInterface) {
                    throw new InvalidArgumentException('Query items must implement AccountingObjectInterface');
                }

                yield $item;
                yield from new Query($item->getItems());
            }
        };
    }

    /**
     * Execute loaded macro
     *
     * @param array<mixed> $args
     */
    public function __call(string $name, array $args): mixed
    {
        if (isset(self::$macros[$name])) {
            return self::$macros[$name]->call($this, ...$args);
        }

        throw new RuntimeException("Call to undefined macro $name()");
    }

    /**
     * Find account from id
     *
     * @throws InvalidAccountException if account does not exist
     */
    public function account(string $accountId): AccountInterface
    {
        $account = null;

        try {
            $account = $this->accounts()->dimension($accountId);
        } catch (InvalidDimensionException) {
            // intentionally empty
        }

        if ($account instanceof AccountInterface) {
            return $account;
        }

        throw new InvalidAccountException("Account $accountId does not exist");
    }

    /**
     * Create a new query that contains only unique account objects
     */
    public function accounts(): Query
    {
        return $this->filter(fn($item) => $item instanceof AccountInterface)->unique();
    }

    /**
     * Get all items in query as array
     *
     * Note that as the query result may be constructed by multiple generators
     * the probability of having multiple items with the same native key is high,
     * which in turn may trigger unexpected behaviour in iterator_to_array().
     *
     * @return array<AccountingObjectInterface>
     */
    public function asArray(): array
    {
        return iterator_to_array($this->getIterator(), false);
    }

    /**
     * Get all items in query wrapped in a container
     */
    public function asContainer(): Container
    {
        return new Container(...$this->asArray());
    }

    /**
     * Summarize items in query
     *
     * Note that in order to make sure that transactions are not counted twice
     * you must use a type filter before calculating summary.
     *
     * @example $query->verifications()->asSummary()
     * @example $query->accounts()->asSummary()
     */
    public function asSummary(): Summary
    {
        return $this->reduce(
            fn($summary, $item) => $summary->withSummary($item->getSummary()),
            new Summary()
        );
    }

    /**
     * Count items in query
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * Find dimension from id
     *
     * @throws InvalidDimensionException if dimension does not exist
     */
    public function dimension(string $dimensionId): DimensionInterface
    {
        $dimension = $this
            ->filter(fn($item) => $item instanceof DimensionInterface && $item->getId() == $dimensionId)
            ->first();

        if ($dimension instanceof DimensionInterface) {
            return $dimension;
        }

        throw new InvalidDimensionException("Dimension $dimensionId does not exist");
    }

    /**
     * Create a new query with only unique dimension objects
     */
    public function dimensions(): Query
    {
        return $this->filter(fn($item) => $item instanceof DimensionInterface)->unique();
    }

    /**
     * Execute callback for all items in query
     */
    public function each(callable $callback): Query
    {
        return $this->lazyEach($callback)->exec();
    }

    /**
     * Execute lazy actions
     */
    public function exec(): Query
    {
        iterator_to_array($this->getIterator());

        return $this;
    }

    /**
     * Create a new query with items that pass a truth test definied in $filter
     *
     * @param callable $filter Should take one argument and return a boolean
     */
    public function filter(callable $filter): Query
    {
        return Query::fromClosure(function () use ($filter) {
            foreach ($this->getIterator() as $item) {
                if ($filter($item)) {
                    yield $item;
                }
            }
        });
    }

    /**
     * Get the first item in query, null if no item is found
     */
    public function first(): ?AccountingObjectInterface
    {
        foreach ($this->getIterator() as $item) {
            return $item;
        }

        return null;
    }

    /**
     * Get iterator for all items in query
     *
     * @return \Generator<AccountingObjectInterface>
     */
    public function getIterator(): \Generator
    {
        yield from ($this->iteratorFactory)();
    }

    /**
     * Check if query does not contain any items
     */
    public function isEmpty(): bool
    {
        foreach ($this->getIterator() as $item) {
            return false;
        }

        return true;
    }

    /**
     * Get the last item in query, null if no item is found
     */
    public function last(): ?AccountingObjectInterface
    {
        $last = null;

        foreach ($this->getIterator() as $item) {
            $last = $item;
        }

        return $last;
    }

    /**
     * Lazy load callback for all items in query
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
     * Lazy load callback for item matching filter
     *
     * @param callable $filter Should take one $item and return a boolean
     * @param callable $callback Called for all items matching $filter
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
     * Limit the number if items returned by query
     */
    public function limit(int $length, int $offset = 0): Query
    {
        $index = -1;
        $count = 0;

        return $this->filter(function () use ($length, $offset, &$index, &$count) {
            $index++;

            if ($offset <= $index && $count < $length) {
                $count++;
                return true;
            }

            return false;
        });
    }

    /**
     * Create a new query by passing each item to $callback
     *
     * @param callable $callback Should take one argument and return it in a modified version
     */
    public function map(callable $callback): Query
    {
        return Query::fromClosure(function () use ($callback) {
            foreach ($this->getIterator() as $item) {
                yield $callback($item);
            }
        });
    }

    /**
     * Create a new query with items ordered using a custom comparison function
     *
     * @param callable $comparator The comparison function must return an integer
     *     less than, equal to, or greater than zero if the first argument is
     *     considered to be respectively less than, equal to, or greater than the
     *     second.
     */
    public function orderBy(callable $comparator): Query
    {
        return Query::fromClosure(function () use ($comparator) {
            $data = $this->asArray();
            usort($data, $comparator);
            yield from $data;
        });
    }

    /**
     * Ceate a new query with items ordered by id
     */
    public function orderById(): Query
    {
        return $this->orderBy(fn($left, $right) => $left->getId() <=> $right->getId());
    }

    /**
     * Reduce items in query to a single value
     *
     * @param callable $callback Should take two values, the result of the previous iteration and next item
     * @param mixed $initial Optional initial value of the first iteration
     * @return mixed The final value of the reduction
     */
    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $this->each(function ($item) use ($callback, &$initial) {
            $initial = $callback($initial, $item);
        });

        return $initial;
    }

    /**
     * Create a new query that contains only unique transaction objects
     */
    public function transactions(): Query
    {
        return $this->filter(fn($item) => $item instanceof TransactionInterface)->unique();
    }

    /**
     * Create a new query with only unique items
     */
    public function unique(): Query
    {
        $uniqueItems = [];

        return $this->filter(function ($item) use (&$uniqueItems) {
            $key = spl_object_id($item);

            if (isset($uniqueItems[$key])) {
                return false;
            }

            return $uniqueItems[$key] = true;
        });
    }

    /**
     * Find verification from id
     *
     * @throws InvalidVerificationException if verification does not exist
     */
    public function verification(string $verificationId): VerificationInterface
    {
        $verification = $this
            ->filter(fn($item) => $item instanceof VerificationInterface && $item->getId() == $verificationId)
            ->first();

        if ($verification instanceof VerificationInterface) {
            return $verification;
        }

        throw new InvalidVerificationException("Verification $verificationId does not exist");
    }

    /**
     * Create a new query with only unique verification objects
     */
    public function verifications(): Query
    {
        return $this->filter(fn($item) => $item instanceof VerificationInterface)->unique();
    }

    /**
     * Create a new query with objects that are, or contains a child that is, matching $filter
     *
     * @param callable $filter Should take one argument and return a boolean
     */
    public function where(callable $filter): Query
    {
        return $this->filter(fn($item) => !(new Query([$item]))->filter($filter)->isEmpty());
    }

    /**
     * Create a new query with objects that not, and does not contain a child that is, matching $filter
     *
     * @param callable $filter Should take one argument and return a boolean
     */
    public function whereNot(callable $filter): Query
    {
        return $this->filter(fn($item) => (new Query([$item]))->filter($filter)->isEmpty());
    }

    /**
     * Create a new query with objects that are or contain account number
     */
    public function whereAccount(string $accountId): Query
    {
        return $this->where(fn($item) => $item instanceof AccountInterface && $item->getId() == $accountId);
    }

    /**
     * Create a new query with objects containing an amount equal to $amount
     */
    public function whereAmountEquals(Amount $amount): Query
    {
        return $this->where(
            fn($item) => $item instanceof TransactionInterface && $item->getAmount()->equals($amount)
        );
    }

    /**
     * Create a new query with objects containing an amount greater than $amount
     */
    public function whereAmountIsGreaterThan(Amount $amount): Query
    {
        return $this->where(
            fn($item) => $item instanceof TransactionInterface && $item->getAmount()->isGreaterThan($amount)
        );
    }

    /**
     * Create a new query with objects containing an amount less than $amount
     */
    public function whereAmountIsLessThan(Amount $amount): Query
    {
        return $this->where(
            fn($item) => $item instanceof TransactionInterface && $item->getAmount()->isLessThan($amount)
        );
    }

    /**
     * Create a new query with objects containing attribute
     */
    public function whereAttribute(string $key): Query
    {
        return $this->where(fn($item) => $item instanceof AttributableInterface && $item->hasAttribute($key));
    }

    /**
     * Create a new query with objects containing attribute value
     */
    public function whereAttributeValue(string $key, mixed $value): Query
    {
        return $this->where(function ($item) use ($key, $value) {
            return $item instanceof AttributableInterface
                && $item->hasAttribute($key)
                && $item->getAttribute($key) == $value;
        });
    }

    private static function fromClosure(\Closure $factory): Query
    {
        $query = new Query([]);
        $query->iteratorFactory = $factory;

        return $query;
    }
}
