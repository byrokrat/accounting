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
 * Copyright 2016-20 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AttributableTrait;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

/**
 * A standard transaction
 */
class Transaction implements TransactionInterface
{
    use AttributableTrait;

    /**
     * @var int
     */
    private $verId;

    /**
     * @var \DateTimeImmutable
     */
    private $transactionDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var Amount
     */
    private $amount;

    /**
     * @var Amount
     */
    private $quantity;

    /**
     * @var AccountInterface
     */
    private $account;

    /**
     * @var DimensionInterface[]
     */
    private $dimensions;

    public function __construct(
        int $verId,
        \DateTimeImmutable $transactionDate,
        string $description,
        string $signature,
        Amount $amount,
        Amount $quantity,
        AccountInterface $account,
        DimensionInterface ...$dimensions
    ) {
        $this->verId = $verId;
        $this->transactionDate = $transactionDate;
        $this->description = $description;
        $this->signature = $signature;
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->account = $account;
        $this->dimensions = $dimensions;
    }

    public function getVerificationId(): int
    {
        return $this->verId;
    }

    public function getTransactionDate(): \DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getQuantity(): Amount
    {
        return $this->quantity;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    public function isAdded(): bool
    {
        return false;
    }

    public function isDeleted(): bool
    {
        return false;
    }

    public function select(): Query
    {
        return new Query(
            array_merge(
                [$this->getAccount(), $this->getAmount()],
                $this->getDimensions()
            )
        );
    }
}
