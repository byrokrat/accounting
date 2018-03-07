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
 * Copyright 2016-18 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Interfaces\Dateable;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Signable;
use byrokrat\accounting\Interfaces\Queryable;
use byrokrat\accounting\Interfaces\Traits\AttributableTrait;
use byrokrat\accounting\Interfaces\Traits\DateableTrait;
use byrokrat\accounting\Interfaces\Traits\DescribableTrait;
use byrokrat\accounting\Interfaces\Traits\SignableTrait;
use byrokrat\amount\Amount;

/**
 * A transaction is a simple value object containing an account and an amount
 */
class Transaction implements Attributable, Dateable, Describable, Queryable, Signable, \IteratorAggregate
{
    use AttributableTrait, DateableTrait, DescribableTrait, SignableTrait;

    /**
     * @var Account Account this transaction concerns
     */
    private $account;

    /**
     * @var Amount The amount of money moved to or from account
     */
    private $amount;

    /**
     * @var Amount The quantity of stuff moved to or from account
     */
    private $quantity;

    /**
     * @var Dimension[] List of registered dimensions
     */
    private $dimensions;

    /**
     * Set transaction values
     */
    public function __construct(Account $account, Amount $amount, Amount $quantity = null, Dimension ...$dimensions)
    {
        $this->account = $account;
        $this->amount = $amount;
        $this->quantity = $quantity ?: new Amount('0');
        $this->dimensions = $dimensions;
    }

    /**
     * Get Account this transaction concerns
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * Get amount of money moved to or from account
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * Get quantity of stuff moved to or from account
     */
    public function getQuantity(): Amount
    {
        return $this->quantity;
    }

    /**
     * Get registered dimensions
     *
     * @return Dimension[]
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    /**
     * Implements the Queryable interface
     */
    public function select(): Query
    {
        return new Query($this->getIterator());
    }

    /**
     * Implements the IteratorAggregate interface
     */
    public function getIterator(): \Generator
    {
        yield $this->getAccount();
        yield $this->getAmount();
        foreach ($this->getDimensions() as $dim) {
            yield $dim;
        }
    }

    /**
     * Generate a simple string representation of transaction
     */
    public function __toString(): string
    {
        return sprintf('%s: %s', $this->getAccount(), $this->getAmount());
    }

    /**
     * Check if this is an added transaction
     */
    public function isAdded(): bool
    {
        return false;
    }

    /**
     * Check if this is a deleted transaction
     */
    public function isDeleted(): bool
    {
        return false;
    }
}
