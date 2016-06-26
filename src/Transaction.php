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
 * A transaction is a simple value object containing an account and an amount
 */
class Transaction implements Queryable, \IteratorAggregate
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * Set transaction values
     */
    public function __construct(Account $account, Amount $amount)
    {
        $this->account = $account;
        $this->amount = $amount;
    }

    /**
     * Get account for this transaction
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * Get amount for this transaction
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * Implements the Queryable interface
     */
    public function query(): Query
    {
        return new Query($this->getIterator());
    }

    /**
     * Implements to IteratorAggregate interface
     */
    public function getIterator(): \Generator
    {
        yield $this->getAccount();
        yield $this->getAmount();
    }
}
