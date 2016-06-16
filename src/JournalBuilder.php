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
 * Builder to simplify the creation of journals
 */
class JournalBuilder
{
    /**
     * @var AccountSet Added accounts
     */
    private $accounts;

    /**
     * @var Journal Added verifications
     */
    private $journal;

    /**
     * Initialize builder
     */
    public function __construct(AccountSet $accounts)
    {
        $this->accounts = $accounts;
        $this->reset();
    }

    /**
     * Reset builder state
     */
    public function reset(): self
    {
        $this->journal = new Journal;
        return $this;
    }

    /**
     * Add a new verification definition
     *
     * @param string             $text            Verification text
     * @param \DateTimeImmutable $date            Date of verification
     * @param array              $transactionData Any number of arrays with an account number and an amount
     */
    public function addVerification(string $text, \DateTimeImmutable $date, array ...$transactionData): self
    {
        $verification = new Verification($text, $date);

        foreach ($transactionData as list($accountNumber, $amount)) {
            $verification->addTransaction(new Transaction(
                $this->accounts->getAccountFromNumber($accountNumber),
                $amount
            ));
        }

        $this->journal->addVerification($verification);

        return $this;
    }

    /**
     * Grab the generated journal
     */
    public function getJournal(): Journal
    {
        return $this->journal;
    }
}
