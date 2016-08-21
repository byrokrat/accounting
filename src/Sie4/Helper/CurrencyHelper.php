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

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\amount\Currency;

/**
 * Helper that keeps track of the defined currency and creates monetary objects
 */
trait CurrencyHelper
{
    /**
     * @var string Name of class to represent parsed amounts
     */
    private $currencyClassname = 'byrokrat\\amount\\Currency\\SEK';

    /**
     * Called when a recoverable runtime error occurs
     */
    abstract public function registerError(string $message);

    /**
     * Called when a currency is definied using #VALUTA
     *
     * @param  string $currency The iso-4217 currency code
     * @return void
     */
    public function onValuta(string $currency)
    {
        $currencyClassname = "byrokrat\\amount\\Currency\\$currency";

        if (!class_exists($currencyClassname)) {
            return $this->registerError("Unknown currency $currency");
        }

        $this->currencyClassname = $currencyClassname;
    }

    /**
     * Called when a monetary amount is encountered
     *
     * @param  string   $amount The raw amount
     * @return Currency Currency object representing amount
     */
    public function createMoney(string $amount): Currency
    {
        return new $this->currencyClassname($amount);
    }
}
