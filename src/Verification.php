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
 * Simple verification value object wrapping a list of transactions
 */
class Verification implements Queryable, \IteratorAggregate
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
     * @var Summary Transaction summaries
     */
    private $summary;

    /**
     * @var Transaction[] Transactions included in verification
     */
    private $transactions = [];

    /**
     * Setup verification data
     *
     * @param string             $text         Free text description
     * @param \DateTimeImmutable $date         Creation date
     * @param Transaction        $transactions Any number of transaction objects
     */
    public function __construct(string $text = '', \DateTimeImmutable $date = null, Transaction ...$transactions)
    {
        $this->text = $text;
        $this->date = $date ?: new \DateTimeImmutable;
        $this->summary = new Summary;
        $this->addTransaction(...$transactions);
    }

    /**
     * Add one ore more new transactions
     */
    public function addTransaction(Transaction ...$transactions): self
    {
        foreach ($transactions as $transaction) {
            $this->transactions[] = $transaction;
            $this->summary->addTransaction($transaction);
        }
        return $this;
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
     * Get included transactions
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Implements the IteratorAggregate interface
     */
    public function getIterator(): \Generator
    {
        foreach ($this->getTransactions() as $transaction) {
            yield $transaction;
        }
    }

    /**
     * For verifications queryable content consists of transactions
     */
    public function query(): Query
    {
        return new Query($this->getIterator());
    }

    /**
     * Check if verification is balanced
     */
    public function isBalanced(): bool
    {
        return $this->summary->isBalanced();
    }

    /**
     * Get the sum of all positive transactions
     */
    public function getMagnitude(): Amount
    {
        return $this->summary->getMagnitude();
    }

    /**
     * Get set of accounts used in this verification
     */
    public function getAccounts(): AccountSet
    {
        return new AccountSet(...$this->query()->accounts()->toArray());
    }
}
