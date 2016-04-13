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

/**
 * Container class for accounts
 */
class ChartOfAccounts
{
    /**
     * @var array Internal chart
     */
    private $accounts = array();

    /**
     * @var string Chart type
     */
    private $type = 'EUBAS97';

    /**
     * Add account
     *
     * @param  Account $account
     * @return void
     */
    public function addAccount(Account $account)
    {
        $nr = intval($account->getNumber());
        $this->accounts[$nr] = $account;
    }

    /**
     * Get account object for number
     *
     * @param  string $number
     * @return Account
     * @throws Exception\OutOfBoundsException If account does not exist
     */
    public function getAccount($number)
    {
        if ($this->accountExists($number)) {
            return $this->accounts[$number];
        } else {
            throw new Exception\OutOfBoundsException("Account number <$number> does not exist");
        }
    }

    /**
     * Get account object for name
     *
     * @param  string $name
     * @return Account
     * @throws Exception\OutOfBoundsException If account does not exist
     */
    public function getAccountFromName($name)
    {
        foreach ($this->accounts as $account) {
            if ($account->getName() == $name) {
                return $account;
            }
        }
        throw new Exception\OutOfBoundsException("Account <$name> does not exist");
    }

    /**
     * Remove account
     *
     * @param  string $number
     * @return void
     */
    public function removeAccount($number)
    {
        unset($this->accounts[$number]);
    }

    /**
     * Validate that account exists in chart
     *
     * @param  string $number
     * @return bool
     */
    public function accountExists($number)
    {
        return isset($this->accounts[$number]);
    }

    /**
     * Get the complete chart of accounts
     *
     * @return array
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set string describing type of chart used
     *
     * @param  string $type
     * @return void
     */
    public function setChartType($type)
    {
        $this->type = (string)$type;
    }

    /**
     * Get string describing the type of chart used
     *
     * @return string
     */
    public function getChartType()
    {
        return $this->type;
    }
}
