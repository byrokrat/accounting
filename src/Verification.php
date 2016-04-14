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

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

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
     * @var Transaction[] Transactions included in verification
     */
    private $transactions = [];

    /**
     * Setup verification data
     *
     * @param string             $text Free text description
     * @param \DateTimeImmutable $date Creation date
     */
    public function __construct(string $text, \DateTimeImmutable $date = null)
    {
        $this->text = $text;
        $this->date = $date ?: new \DateTimeImmutable;
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
     */
    public function addTransaction(Transaction ...$transactions)
    {
        foreach ($transactions as $transaction) {
            $this->transactions[] = $transaction;
        }
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
     * Get array of unique accounts used in this verification
     *
     * @return Account[] List of accounts using account numbers as keys
     */
    public function getAccounts(): array
    {
        $accounts = [];
        foreach ($this->getTransactions() as $transaction) {
            $account = $transaction->getAccount();
            $accounts[$account->getNumber()] = $account;
        }

        return $accounts;
    }

    /**
     * Get transaction difference. 0 if verification is balanced
     */
    public function getDifference(): Amount
    {
        $diff = null;
        foreach ($this->getTransactions() as $transaction) {
            if (!isset($diff)) {
                $diff = $transaction->getAmount();
                continue;
            }
            $diff = $diff->add($transaction->getAmount());
        }

        return $diff ?: new Amount('0');
    }

    /**
     * Validate that this verification is balanced
     */
    public function isBalanced(): bool
    {
        return $this->getDifference()->isZero();
    }
}
