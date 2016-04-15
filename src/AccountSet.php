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

/**
 * Container for multiple account objects
 */
class AccountSet implements \IteratorAggregate
{
    /**
     * @var Account[] Loaded accounts
     */
    private $accounts = [];

    /**
     * Optionally add accounts at construct
     */
    public function __construct(Account ...$accounts)
    {
        $this->addAccount(...$accounts);
    }

    /**
     * Add one ore more accounts to container
     *
     * Adding the same account number multiple times will overwrite previous value
     */
    public function addAccount(Account ...$accounts): self
    {
        foreach ($accounts as $account) {
            $this->accounts[$account->getNumber()] = $account;
        }
        return $this;
    }

    /**
     * Check if account number exists in set
     */
    public function contains(int $number): bool
    {
        return isset($this->accounts[$number]);
    }

    /**
     * Remove account from set
     */
    public function removeAccount(int $number)
    {
        unset($this->accounts[$number]);
    }

    /**
     * Get account object from number
     *
     * @throws Exception\OutOfBoundsException If account does not exist
     */
    public function getAccount(int $number): Account
    {
        if (!$this->contains($number)) {
            throw new Exception\OutOfBoundsException("Account number <$number> does not exist");
        }

        return $this->accounts[$number];
    }

    /**
     * Get account object from name
     *
     * @throws Exception\OutOfBoundsException If account does not exist
     */
    public function getAccountFromName(string $name): Account
    {
        foreach ($this->accounts as $account) {
            if ($account->getName() == $name) {
                return $account;
            }
        }
        throw new Exception\OutOfBoundsException("Account <$name> does not exist");
    }

    /**
     * Implements the IteratorAggregate interface
     *
     * @return \Traversable Yields account numbers as keys and Account objects as values
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->accounts as $number => $account) {
            yield $number => $account;
        }
    }
}
