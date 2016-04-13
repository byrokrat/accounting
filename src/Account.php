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

use Exception\InvalidArgumentException;

/**
 * Simple Account class
 */
class Account
{
    /**
     * @var string Account number
     */
    private $number;

    /**
     * @var string Account type
     */
    private $type;

    /**
     * @var string Account name
     */
    private $name;

    /**
     * Constructor
     *
     * @param  string $number
     * @param  string $type   Account type (T, S, I or K)
     * @param  string $name
     * @throws Exception\InvalidArgumentException If any data is invalid
     */
    public function __construct($number, $type, $name)
    {
        $number = intval($number);
        if ($number < 1000 || $number > 9999) {
            throw new Exception\InvalidArgumentException("Account must be numeric, 999 < number < 10000");
        }

        $this->number = (string)$number;

        if (!in_array($type, array('T', 'S', 'I', 'K'))) {
            throw new Exception\InvalidArgumentException("Account type must be T, S, I or K");
        }

        $this->type = $type;

        if (!is_string($name) || empty($name)) {
            throw new Exception\InvalidArgumentException("Account name can not be empty");
        }

        $this->name = $name;
    }

    /**
     * Get account number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get account type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get account name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Validate that $account equals this instance
     *
     * @param  Account $account
     * @return bool
     */
    public function equals(Account $account)
    {
        if ($this->getNumber() != $account->getNumber()
            || $this->getType() != $account->getType()
            || $this->getName() != $account->getName()
        ) {
            return false;
        }
        return true;
    }
}
