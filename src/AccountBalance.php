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

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

/**
 * Managing balance for a specific account
 */
class AccountBalance
{
    /**
     * @var Account The handled account
     */
    private $account;

    /**
     * @var Amount Incoming balance for account
     */
    private $incoming;

    /**
     * @var TransactionSet Transactions included in calculation
     */
    private $transactions;

    /**
     * Setup balance data
     *
     * @param Account        $account      The handled account
     * @param Amount         $incoming     Incoming balance for account
     * @param TransactionSet $transactions Collection of transactions
     */
    public function __construct(Account $account, Amount $incoming, TransactionSet $transactions = null)
    {
        $this->account = $account;
        $this->incoming = $incoming;
        $this->transactions = $transactions ?: new TransactionSet;
    }

    /**
     * Get handled account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * Add one ore more new transactions
     *
     * @return self To enable chaining
     * @throws Exception\InvalidArgumentException If a transaction does not match account
     */
    public function addTransaction(Transaction ...$transactions): self
    {
        foreach ($transactions as $transaction) {
            if (!$this->getAccount()->equals($transaction->getAccount())) {
                throw new Exception\InvalidArgumentException(
                    sprintf(
                        "Transaction account '%s %s' does not match handled account '%s %s'",
                        $transaction->getAccount()->getNumber(),
                        $transaction->getAccount()->getName(),
                        $this->getAccount()->getNumber(),
                        $this->getAccount()->getName()
                    )
                );
            }
            $this->transactions->addTransaction($transaction);
        }
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
     * Get incoming balance for account
     */
    public function getIncomingBalance(): Amount
    {
        return $this->incoming;
    }

    /**
     * Get outgoing balance for account
     */
    public function getOutgoingBalance(): Amount
    {
        return $this->getTransactions()->getSum()->add(
            $this->getIncomingBalance()
        );
    }
}
