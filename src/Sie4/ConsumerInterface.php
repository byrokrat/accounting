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
 * Defines callbacks for the different row parsing expressions
 *
 * The naming convention follows the SIE spec, which means swedish vocabulary
 * and swedish spelling of some english sounding words.
 */
interface ConsumerInterface
{
    /**
     * Called when a currency is definied using #VALUTA
     *
     * @param  string $currency Te iso-4217 currency code
     * @return string The accepted currency code
     * @see    Helper\CurrencyBuilder
     */
    public function onCurrency(string $currency): string;

    /**
     * Called when a monetary amount is encountered
     *
     * @param  string   $amount The raw amount
     * @return Currency Currency object representing amount
     * @see    Helper\CurrencyBuilder
     */
    public function onAmount(string $amount): Currency;

    public function onUnknown(string $label, array $fields);

    // TODO should english names be used after all??
    public function onAdress(string $kontakt, string $utdelningsadr, string $postadr, string $tel);
}
