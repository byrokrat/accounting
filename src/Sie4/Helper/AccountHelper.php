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

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\Account;
use byrokrat\accounting\AccountFactory;

/**
 * Helper that keeps track of account objects
 */
trait AccountHelper
{
    /**
     * @var string[] Map of account type identifier to class name
     */
    private $accountTypeMap = [
        'T' => Account\Asset::CLASS,
        'S' => Account\Debt::CLASS,
        'K' => Account\Cost::CLASS,
        'I' => Account\Earning::CLASS
    ];

    /**
     * @var Account[] Created account objects
     */
    private $accounts = [];

    /**
     * @var AccountFactory
     */
    private $factory;

    /**
     * Called when a recoverable runtime error occurs
     *
     * @param  string $message A message describing the error
     * @return void
     */
    abstract public function registerError(string $message);

    /**
     * Set factory to use when creating account objects
     */
    public function setAccountFactory(AccountFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Called when an #KONTO post is encountered
     *
     * @param  integer $number      Number of account
     * @param  string  $description Free text description of account
     * @return Account The generated account object
     */
    public function onKonto(int $number, string $description): Account
    {
        return $this->accounts[$number] = $this->factory->createAccount($number, $description);
    }

    /**
     * Called when an #KTYP post is encountered
     *
     * @param  integer $number  Number of account
     * @param  string  $type    Type of account (T, S, I or K)
     * @return Account The generated account object
     */
    public function onKtyp(int $number, string $type): Account
    {
        if (!isset($this->accountTypeMap[$type])) {
            $this->registerError("Unknown type $type for account number $number");
            return $this->getAccount($number);
        }

        return $this->accounts[$number] = new $this->accountTypeMap[$type](
            $number,
            $this->getAccount($number)->getDescription(),
            $this->getAccount($number)->getAttributes()
        );
    }

    /**
     * Called when an #ENHET post is encountered
     */
    public function onEnhet(int $account, string $unit)
    {
        $this->getAccount($account)->setAttribute('unit', $unit);
    }

    /**
     * Called when an #SRU post is encountered
     */
    public function onSru(int $account, int $sru)
    {
        $this->getAccount($account)->setAttribute('sru', $sru);
    }

    /**
     * Get account from internal store using account number as key
     */
    public function getAccount(int $number): Account
    {
        if (!isset($this->accounts[$number])) {
            $this->onKonto($number, 'UNSPECIFIED');
        }

        return $this->accounts[$number];
    }
}
