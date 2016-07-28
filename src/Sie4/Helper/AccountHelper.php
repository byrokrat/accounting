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
     * Inject account factory
     */
    public function setAccountFactory(AccountFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Called when an #KONTO row is encountered
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
     * Called when an #KTYP row is encountered
     *
     * @param  integer $number  Number of account
     * @param  string  $type    Type of account (T, S, I or K)
     * @return Account The generated account object
     */
    public function onKtyp(int $number, string $type): Account
    {
        if (!isset($this->accountTypeMap[$type])) {
            // TODO maybe not throw an exception but instead log the error...
            // $this->registerError("Unknown type $type for account number $number");
            // return $this->getAccount($number);
            throw new \byrokrat\accounting\Exception\OutOfBoundsException("Unknown account type $type");
        }

        // TODO säkerställ att attribut flyttas över till det nya objektet
            // kräver någon form av getAttributes(): array till Attributable
            // kan också användas för att göra Template Attributable...

        // när dessa två saker är fixade, plus att jag har test för detta, så är det dax att gå vidare...

        return $this->accounts[$number] = new $this->accountTypeMap[$type](
            $number,
            $this->getAccount($number)->getDescription()
        );
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
