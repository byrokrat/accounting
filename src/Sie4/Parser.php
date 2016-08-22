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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Account;
use byrokrat\accounting\AccountFactory;
use byrokrat\accounting\Dimension;
use byrokrat\amount\Currency;

/**
 * Callbacks for parsing expressions found in Grammar
 */
class Parser extends Grammar
{
    use Helper\AccountHelper, Helper\CurrencyHelper, Helper\DimensionHelper, Helper\ErrorHelper;

    /**
     * Set factory to use when creating account objects
     */
    public function __construct(AccountFactory $factory = null)
    {
        $this->setAccountFactory($factory ?: new AccountFactory);
    }

    /**
     * Parse SIE content
     *
     * @param  string $content Raw SIE content to parse
     * @return void
     * @api    This is the main access point for parsing Sie4 data
     */
    public function parse($content)
    {
        $this->resetErrorState();

        try {
            parent::parse($content);
        } catch (\Exception $e) {
            $this->registerError($e->getMessage());
        }

        $this->validateErrorState();
    }

    /**
     * Called when a #FLAGGA row is encountered
     *
     * @param  boolean $flag
     * @return void
     */
    public function onFlagga(bool $flag)
    {
    }

    /**
     * Called when an #ADRESS row is encountered
     *
     * @param  string $contact  Contact addressee
     * @param  string $address  Street address
     * @param  string $location Secondary address field
     * @param  string $phone    Phone number
     * @return void
     */
    public function onAdress(string $contact, string $address, string $location, string $phone)
    {
    }

    /**
     * Called when an #OMFATTN row is encountered
     *
     * @param  DateTime $date
     * @return void
     */
    public function onOmfattn(\DateTimeImmutable $date)
    {
    }

    /**
     * Called when a #SIETYP row is encountered
     *
     * @param  int $ver The SIE version the parsed file targets
     * @return void
     */
    public function onSietyp(int $ver)
    {
    }

    /**
     * Called when an #IB row is encountered
     *
     * @param  int      $year     0 means current year, -1 preavious, and so on..
     * @param  Account  $account  Account balance is specified for
     * @param  Currency $balance  The incoming balance
     * @param  integer  $quantity Quantity if registered for account
     * @return void
     */
    public function onIb(int $year, Account $account, Currency $balance, int $quantity = 0)
    {
    }

    /**
     * Called when an #OIB row is encountered
     *
     * @param  int         $year     0 means current year, -1 preavious, and so on..
     * @param  Account     $account  Account balance is specified for
     * @param  Dimension[] $objects  Accounting objects balance is specified for
     * @param  Currency    $balance  The incoming balance
     * @param  integer     $quantity Quantity if registered for account
     * @return void
     */
    public function onOib(int $year, Account $account, array $objects, Currency $balance, int $quantity = 0)
    {
    }
}
