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

/**
 * Fetch summaries for set of accounts
 */
class AccountSummaryBuilder
{
    /**
     * @var VerificationSet Verifications to process
     */
    private $verifications;

    /**
     * Set verifications to process
     *
     * @return self To enable chaining
     */
    public function setVerifications(VerificationSet $verifications): self
    {
        $this->verifications = $verifications;
        return $this;
    }

    /**
     * Calculate summaries for accounts
     *
     * @param  AccountSet $accounts
     * @return AccountSet
     */
    public function processAccounts(AccountSet $accounts): AccountSet
    {
        $summaries = new AccountSet;
        $processor = new TransactionProcessor;

        foreach ($accounts as $account) {

            // TODO incoming balance must be loaded somehow... Currency is unknown here..
            // This forces the use of the standard Amount clas...
            $summary = new AccountSummary($account, new \byrokrat\amount\Amount('0'));

            $processor->onAccount($account, function (Transaction $transaction) use ($summary) {
                $summary->addTransaction($transaction);
            });

            $summaries->addAccount($summary);
        }

        $processor->process($this->verifications);

        return $summaries;
    }
}
