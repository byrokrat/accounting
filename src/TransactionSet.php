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
class TransactionSet implements \IteratorAggregate
{
    /**
     * @var Transaction[] Loaded transactions
     */
    private $transactions = [];

    /**
     * Optionally load transactions at construct
     */
    public function __construct(Transaction ...$transactions)
    {
        $this->addTransaction(...$transactions);
    }

    /**
     * Add one or more transactions
     *
     * @return self To enable chaining
     */
    public function addTransaction(Transaction ...$transactions): self
    {
        foreach ($transactions as $transaction) {
            $this->transactions[] = $transaction;
        }
        return $this;
    }

    /**
     * Implements the IteratorAggregate interface
     *
     * @return \Traversable Yields index numbers as keys and Transaction objects as values
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->transactions as $index => $transaction) {
            yield $index => $transaction;
        }
    }

    /**
     * Get the calculated sum of all transactions
     */
    public function getSum(): Amount
    {
        $sum = null;
        foreach ($this->getIterator() as $transaction) {
            if (!isset($sum)) {
                $sum = $transaction->getAmount();
                continue;
            }
            $sum = $sum->add($transaction->getAmount());
        }

        return $sum ?: new Amount('0');
    }

    /**
     * Get set of accounts used in this verification
     */
    public function getAccounts(): AccountSet
    {
        $accounts = new AccountSet;
        foreach ($this->getIterator() as $transaction) {
            $accounts->addAccount($transaction->getAccount());
        }

        return $accounts;
    }
}
