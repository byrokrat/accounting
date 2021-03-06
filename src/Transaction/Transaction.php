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

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AbstractAccountingObject;
use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidTransactionException;
use byrokrat\accounting\Summary;
use Money\Money;

final class Transaction extends AbstractAccountingObject implements TransactionInterface
{
    private AccountingDate $transactionDate;

    /**
     * @param array<DimensionInterface> $dimensions
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        private AccountInterface $account,
        private Money $amount,
        private string $verificationId = '0',
        ?AccountingDate $transactionDate = null,
        string $description = '',
        private string $signature = '',
        private array $dimensions = [],
        array $attributes = [],
        private bool $added = false,
        private bool $deleted = false,
    ) {
        parent::__construct(
            id: md5($this->verificationId . spl_object_id($this) . time()),
            description: $description,
            attributes: $attributes,
        );

        $this->transactionDate = $transactionDate ?: AccountingDate::today();

        $this->account->addTransaction($this);

        foreach ($this->dimensions as $dimension) {
            if (!$dimension instanceof DimensionInterface) {
                throw new InvalidArgumentException('Dimension must implement DimensionInterface');
            }

            $dimension->addTransaction($this);
        }

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        if ($this->added && $this->deleted) {
            throw new InvalidTransactionException('Transaction can not be both added and deleted');
        }
    }

    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

    public function getTransactionDate(): AccountingDate
    {
        return $this->transactionDate;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getAmount(): Money
    {
        return $this->amount;
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
        return $this->added;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function getItems(): array
    {
        return [$this->getAccount(), ...$this->getDimensions()];
    }

    public function getSummary(): Summary
    {
        return $this->isDeleted()
            ? Summary::fromAmount($this->amount->subtract($this->amount))
            : Summary::fromAmount($this->amount);
    }
}
