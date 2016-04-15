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
 * Container for multiple verification objects
 */
class VerificationSet implements \IteratorAggregate
{
    /**
     * @var Verification[] Loaded verifications
     */
    private $verifications = [];

    /**
     * Optionally add verifications at construct
     */
    public function __construct(Verification ...$verifications)
    {
        $this->addVerification(...$verifications);
    }

    /**
     * Add one ore more verifications to container
     *
     * @throws Exception\UnexpectedValueException If verification is unbalanced
     */
    public function addVerification(Verification ...$verifications): self
    {
        foreach ($verifications as $verification) {
            if (!$verification->isBalanced()) {
                throw new Exception\UnexpectedValueException('Unable to add unbalanced verification');
            }
            $this->verifications[] = $verification;
        }
        return $this;
    }

    /**
     * Implements the IteratorAggregate interface
     *
     * @return \Traversable Yields serial numbers as keys and Verification objects as values
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->verifications as $serial => $verification) {
            yield ++$serial => $verification;
        }
    }

    /**
     * Get set of accounts used in verifications
     */
    public function getAccounts(): AccountSet
    {
        $accounts = new AccountSet;
        foreach ($this->getIterator() as $verification) {
            foreach ($verification->getAccounts() as $account) {
                $accounts->addAccount($account);
            }
        }

        return $accounts;
    }
}
