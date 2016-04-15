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
 * Builder to simplify the creation of account sets
 */
class AccountSetBuilder
{
    /**
     * @var AccountSet Created accounts
     */
    private $accounts;

    /**
     * Initialize builder
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Reset builder state
     *
     * @return self To enable chaining
     */
    public function reset(): self
    {
        $this->accounts = new AccountSet;
        return $this;
    }

    /**
     * Create account from values
     *
     * @param  int    $number          Account number
     * @param  string $name            Name of account
     * @param  Amount $incomingBalance The incoming balance of account
     * @return self   To enable chaining
     */
    public function createAccount(int $number, string $name, Amount $incomingBalance = null): self
    {
        if ($number < 2000) {
            $account = new Account\Asset($number, $name);
        } elseif ($number < 3000) {
            $account = new Account\Debt($number, $name);
        } elseif ($number < 4000) {
            $account = new Account\Earning($number, $name);
        } else {
            $account = new Account\Cost($number, $name);
        }

        if ($incomingBalance) {
            $account = new AccountSummary($account, $incomingBalance);
        }

        $this->accounts->addAccount($account);

        return $this;
    }

    /**
     * Grab the generated accounts
     */
    public function getAccounts(): AccountSet
    {
        return $this->accounts;
    }
}
