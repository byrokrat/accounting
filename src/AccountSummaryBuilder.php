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
 * Fetch summaries for set of accounts
 */
class AccountSummaryBuilder
{
    /**
     * @var Journal Verifications to process
     */
    private $journal;

    /**
     * @var Amount Default incoming balance
     */
    private $defaultIncoming;

    /**
     * Set verifications to process
     *
     * @return self To enable chaining
     */
    public function setJournal(Journal $journal): self
    {
        $this->journal = $journal;
        return $this;
    }

    /**
     * Get verifications to process
     *
     * @throws Exception\OutOfBoundsException If journal is not set
     */
    public function getJournal(): Journal
    {
        if (!isset($this->journal)) {
            throw new Exception\OutOfBoundsException(
                'Journal not loaded, did you call setJournal()?'
            );
        }
        return $this->journal;
    }

    /**
     * Set the default incoming balance, used if a plain account is passed
     *
     * @return self To enable chaining
     */
    public function setDefaultIncomingBalance(Amount $defaultIncoming): self
    {
        $this->defaultIncoming = $defaultIncoming;
        return $this;
    }

    /**
     * Get the default incoming balance
     *
     * @throws Exception\OutOfBoundsException If default balance is not set
     */
    public function getDefaultIncomingBalance(): Amount
    {
        if (!isset($this->defaultIncoming)) {
            throw new Exception\OutOfBoundsException(
                'Default balance not set, did you call setDefaultIncomingBalance()?'
            );
        }
        return $this->defaultIncoming;
    }

    /**
     * Calculate summaries for accounts
     *
     * Please note that if $accounts contains AccountSummary objects these
     * will be edited and not cloned.
     *
     * @param  AccountSet $accounts
     * @return AccountSet
     */
    public function processAccounts(AccountSet $accounts): AccountSet
    {
        $summaries = new AccountSet;
        $query = (new Query($this->getJournal()))->transactions();

        foreach ($accounts as $account) {
            $summary = $account instanceof AccountSummary
                ? $account
                : new AccountSummary($account, $this->getDefaultIncomingBalance());

            $query->lazyOn(
                function (Transaction $transaction) use ($summary) {
                    return $transaction->getAccount()->equals($summary);
                },
                function (Transaction $transaction) use ($summary) {
                    $summary->addTransaction($transaction);
                }
            );

            $summaries->addAccount($summary);
        }

        $query->exec();

        return $summaries;
    }
}
