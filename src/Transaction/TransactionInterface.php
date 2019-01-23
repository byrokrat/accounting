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
 * Copyright 2016-19 Hannes Forsgård
 */

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\QueryableInterface;
use byrokrat\amount\Amount;

/**
 * A pure transaction is an amount moved to or from an account
 */
interface TransactionInterface extends AttributableInterface, QueryableInterface
{
    /**
     * Get the id of the verification this transaction is a part of
     */
    public function getVerificationId(): int;

    /**
     * Get transaction date
     */
    public function getTransactionDate(): \DateTimeImmutable;

    /**
     * Get free text description
     */
    public function getDescription(): string;

    /**
     * Get transaction signature
     */
    public function getSignature(): string;

    /**
     * Get amount of money moved to or from account
     */
    public function getAmount(): Amount;

    /**
     * Get quantity of stuff moved to or from account
     */
    public function getQuantity(): Amount;

    /**
     * Get Account this transaction concerns
     */
    public function getAccount(): AccountInterface;

    /**
     * Get registered dimensions
     *
     * @return DimensionInterface[]
     */
    public function getDimensions(): array;

    /**
     * Check if this is an added transaction
     */
    public function isAdded(): bool;

    /**
     * Check if this is a deleted transaction
     */
    public function isDeleted(): bool;
}
