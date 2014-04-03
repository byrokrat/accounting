<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\accounting;

use ledgr\accounting\Exception\InvalidChartException;
use ledgr\accounting\Exception\InvalidAccountException;

/**
 * Container class for charts of accounts.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
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
     * @param  string                  $number
     * @return Account
     * @throws InvalidAccountException If account does not exist
     */
    public function getAccount($number)
    {
        if ($this->accountExists($number)) {
            return $this->accounts[$number];
        } else {
            $msg = "Account number '$number' does not exist";
            throw new InvalidAccountException($msg);
        }
    }

    /**
     * Get account object for name
     *
     * @param  string                  $name
     * @return Account
     * @throws InvalidAccountException If account does not exist
     */
    public function getAccountFromName($name)
    {
        foreach ($this->accounts as $account) {
            if ($account->getName() == $name) {
                return $account;
            }
        }
        $msg = "Account name '$name' does not exist";
        throw new InvalidAccountException($msg);
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
