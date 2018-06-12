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
 * Copyright 2016-18 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\Account;
use byrokrat\accounting\Dimension;
use byrokrat\accounting\Helper\AttributableTrait;
use byrokrat\accounting\Helper\DateableTrait;
use byrokrat\accounting\Helper\DescribableTrait;
use byrokrat\accounting\Helper\QueryableTrait;
use byrokrat\accounting\Helper\SignableTrait;
use byrokrat\amount\Amount;

/**
 * A standard transaction
 */
class Transaction implements TransactionInterface
{
    use AttributableTrait, DateableTrait, DescribableTrait, QueryableTrait, SignableTrait;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var Amount
     */
    private $quantity;

    /**
     * @var Dimension[]
     */
    private $dimensions;

    public function __construct(Account $account, Amount $amount, Amount $quantity = null, Dimension ...$dimensions)
    {
        $this->account = $account;
        $this->amount = $amount;
        $this->quantity = $quantity ?: new Amount('0');
        $this->dimensions = $dimensions;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function getIterator(): iterable
    {
        yield $this->getAccount();
        yield $this->getAmount();
        foreach ($this->getDimensions() as $dim) {
            yield $dim;
        }
    }

    public function getQuantity(): Amount
    {
        return $this->quantity;
    }

    public function isAdded(): bool
    {
        return false;
    }

    public function isDeleted(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return sprintf('%s: %s', $this->getAccount(), $this->getAmount());
    }
}
