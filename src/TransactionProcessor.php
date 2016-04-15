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
 * Find transactions related to account using callbacks
 */
class TransactionProcessor
{
    /**
     * @var callable[] Map of account numbers to callbacks
     */
    private $callbacks = [];

    /**
     * Load callback for account
     *
     * @param  Account  $account  Registering multiple callbacks for the same account triggers overwrite
     * @param  callable $callback Should take a Transaction object as sole argument
     * @return void
     */
    public function onAccount(Account $account, callable $callback)
    {
        $this->callbacks[$account->getNumber()] = $callback;
    }

    /**
     * Process transactions in verifications and fire registered callbacks
     */
    public function process(VerificationSet $verifications)
    {
        foreach ($verifications as $verification) {
            foreach ($verification->getTransactions() as $transaction) {
                if (isset($this->callbacks[$transaction->getAccount()->getNumber()])) {
                    $this->callbacks[$transaction->getAccount()->getNumber()]($transaction);
                }
            }
        }
    }
}
