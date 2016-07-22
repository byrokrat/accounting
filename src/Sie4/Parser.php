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

namespace byrokrat\accounting\Sie4;

use byrokrat\amount\Currency;

/**
 * Callbacks for parsing expressions found in Grammar
 */
class Parser extends Grammar
{
    use Helper\CurrencyBuilder;

    /**
     * Called when an unknown row is encountered
     *
     * @param  string   $label Row label
     * @param  string[] $vars  Row variables
     * @return mixed    Undefined
     */
    public function onUnknown(string $label, array $vars)
    {
    }

    /**
     * Called when an #FLAGGA row is encountered
     *
     * @param  boolean $flag
     * @return mixed   Undefined
     */
    public function onFlag(bool $flag)
    {
    }

    /**
     * Called when an #SIETYP row is encountered
     *
     * @param  int   $ver The SIE version the parsed file targets
     * @return mixed Undefined
     */
    public function onSieVersion(int $ver)
    {
    }

    /**
     * Called when an #IB row is encountered
     *
     * @param  int      $year     0 means current year, -1 preavious, and so on..
     * @param  string   $account  Account balance is specified for
     * @param  Currency $balance  Te incoming balance
     * @param  integer  $quantity Quantity if registered for account
     * @return mixed    Undefined
     */
    public function onIncomingBalance(int $year, string $account, Currency $balance, int $quantity = 0)
    {
    }

    /**
     * Called when an #OMFATTN row is encountered
     *
     * @param  DateTime $date
     * @return mixed    Undefined
     */
    public function onMagnitudeDate(\DateTime $date)
    {
    }

    /**
     * Called when an #ADRESS row is encountered
     *
     * @param  string $contact  Contact addressee
     * @param  string $address  Street address
     * @param  string $location Secondary address field
     * @param  string $phone    Phone number
     * @return mixed  Undefined
     */
    public function onAddress(string $contact, string $address, string $location, string $phone)
    {
    }
}
