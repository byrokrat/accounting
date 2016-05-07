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

/**
 * Simple verification value object wrapping a list of transactions
 */
class Verification
{
    /**
     * @var string Free text description
     */
    private $text;

    /**
     * @var \DateTimeImmutable Creation date
     */
    private $date;

    /**
     * @var TransactionSet Transactions included in verification
     */
    private $transactions;

    /**
     * Setup verification data
     *
     * @param string             $text         Free text description
     * @param \DateTimeImmutable $date         Creation date
     * @param TransactionSet     $transactions Collection of transactions
     */
    public function __construct(string $text, \DateTimeImmutable $date = null, TransactionSet $transactions = null)
    {
        $this->text = $text;
        $this->date = $date ?: new \DateTimeImmutable;
        $this->transactions = $transactions ?: new TransactionSet;
    }

    /**
     * Get text describing verification
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get transaction date
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Add one ore more new transactions
     *
     * @return self To enable chaining
     */
    public function addTransaction(Transaction ...$transactions): self
    {
        $this->transactions->addTransaction(...$transactions);
        return $this;
    }

    /**
     * Get included transactions
     */
    public function getTransactions(): TransactionSet
    {
        return $this->transactions;
    }

    /**
     * Validate that this verification is balanced
     */
    public function isBalanced(): bool
    {
        return $this->getTransactions()->getSum()->isZero();
    }

    /**
     * Get set of accounts used in this verification
     */
    public function getAccounts(): AccountSet
    {
        return $this->getTransactions()->getAccounts();
    }
}
