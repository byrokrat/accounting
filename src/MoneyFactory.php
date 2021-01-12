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
 * Copyright 2016-21 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidAmountException;
use Money\Currency;
use Money\Money;

class MoneyFactory
{
    public const DEFAULT_CURRENCY = 'SEK';

    private Currency $currency;

    public function __construct(Currency $currency = null)
    {
        $this->setCurrency($currency ?: new Currency(self::DEFAULT_CURRENCY));
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function createMoney(string $rawMoney): Money
    {
        if (!preg_match('/^(-?)0*(\d*)\.?(\d?\d?)$/', $rawMoney, $matches)) {
            throw new InvalidAmountException("Invalid monetary amount $rawMoney, expected xxx or xxx.xx");
        }

        list(, $negation, $leading, $fraction) = $matches;

        if (empty($leading)) {
            $leading = '0';
        }

        if (strlen($fraction) == 1) {
            $fraction .= '0';
        }

        if (empty($fraction)) {
            $fraction = '00';
        }

        $money = ltrim($leading . $fraction, '0');

        if (empty($money)) {
            $money = '0';
        }

        return new Money($negation . $money, $this->currency);
    }
}
