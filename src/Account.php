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

declare(strict_types=1);

namespace byrokrat\accounting;

use Exception\InvalidArgumentException;

/**
 * Account value object containing a number and a name
 */
abstract class Account
{
    /**
     * Identifier for asset accounts
     */
    const ASSET = 'T';

    /**
     * Identifier for debt accounts
     */
    const DEBT = 'S';

    /**
     * Identifier for earning accounts
     */
    const EARNING = 'I';

    /**
     * Identifier for cost accounts
     */
    const COST = 'K';

    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $name;

    /**
     * @throws Exception\InvalidArgumentException If number is invalid
     */
    public function __construct(int $number, string $name)
    {
        if ($number < 1000 || $number > 9999) {
            throw new Exception\InvalidArgumentException("Account must be greater than 999 and lesser than 10000");
        }
        $this->number = $number;
        $this->name = $name;
    }

    /**
     * Get account type identifier
     */
    abstract public function getType(): string;

    /**
     * Get 4 digit account number
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Get name of account
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if $account equals this account
     */
    public function equals(Account $account): bool
    {
        return (
            $this->getType() == $account->getType()
            && $this->getNumber() == $account->getNumber()
            && $this->getName() == $account->getName()
        );
    }
}
