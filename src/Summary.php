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
 * Create summaries for collections of transactions
 */
class Summary
{
    /**
     * @var Amount Incoming balance
     */
    private $incoming;

    /**
     * @var Amount Current balance
     */
    private $balance;

    /**
     * @var Amount Current debit summary
     */
    private $debit;

    /**
     * @var Amount Current credit summary
     */
    private $credit;

    /**
     * @var Transaction[] Transactions included in summary
     */
    private $transactions = [];

    /**
     * Setup calculation starting points
     *
     * @param Amount  $incoming Incoming balance
     */
    public function __construct(Amount $incoming = null)
    {
        if ($incoming) {
            $this->initialize($incoming);
        }
    }

    /**
     * Set calculation starting points
     */
    public function initialize(Amount $incoming)
    {
        $this->incoming = $incoming;
        $this->balance = $incoming;
        $this->debit = $incoming->subtract($incoming);
        $this->credit = $this->debit;
    }

    /**
     * Check if calculations has been initialized
     */
    public function isInitialized(): bool
    {
        return isset($this->incoming);
    }

    /**
     * Add transaction to summary calculations
     */
    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->isInitialized()) {
            $this->initialize($transaction->getAmount()->subtract($transaction->getAmount()));
        }

        $this->transactions[] = $transaction;

        $this->balance = $this->balance->add($transaction->getAmount());

        if ($transaction->getAmount()->isPositive()) {
            $this->debit = $this->debit->add($transaction->getAmount());
        } else {
            $this->credit = $this->credit->add($transaction->getAmount()->getAbsolute());
        }

        return $this;
    }

    /**
     * Get calculated incoming balance
     */
    public function getIncomingBalance(): Amount
    {
        $this->checkState();
        return $this->incoming;
    }

    /**
     * Get calculated outgoing balance
     */
    public function getOutgoingBalance(): Amount
    {
        $this->checkState();
        return $this->balance;
    }

    /**
     * Get calculated debit summary
     */
    public function getDebit(): Amount
    {
        $this->checkState();
        return $this->debit;
    }

    /**
     * Get calculated credit summary
     */
    public function getCredit(): Amount
    {
        $this->checkState();
        return $this->credit;
    }

    /**
     * Check if summary is balanced
     */
    public function isBalanced(): bool
    {
        return $this->getDebit()->equals($this->getCredit());
    }

    /**
     * Get collection magnitude (absolute value of debit or credit for balanced collections)
     *
     * @throws Exception\RuntimeException if summary is not balanced
     */
    public function getMagnitude()
    {
        if (!$this->isBalanced()) {
            throw new Exception\RuntimeException('Unable to calculate magnitude of unbalanced collection');
        }

        return $this->getDebit();
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
     * @throws Exception\RuntimeException if summaries are not initialized
     */
    private function checkState()
    {
        if (!$this->isInitialized()) {
            throw new Exception\RuntimeException('Unable to calculate summaries on an empty set of transactions');
        }
    }
}
