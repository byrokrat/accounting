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

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\QueryableInterface;
use byrokrat\amount\Amount;

/**
 * A pure transaction is an amount moved to or from an account
 */
interface TransactionInterface extends AttributableInterface, QueryableInterface, \IteratorAggregate
{
    /**
     * Get Account this transaction concerns
     */
    public function getAccount(): AccountInterface;

    /**
     * Get amount of money moved to or from account
     */
    public function getAmount(): Amount;

    /**
     * Get transaction date
     */
    public function getDate(): \DateTimeImmutable;

    /**
     * Check if a transaction date has been set
     */
    public function hasDate(): bool;

    /**
     * Set a new transaction date
     */
    public function setDate(\DateTimeImmutable $date): void;

    /**
     * Get free text description
     */
    public function getDescription(): string;

    /**
     * Set free text description
     */
    public function setDescription(string $description): void;

    /**
     * Get registered dimensions
     *
     * @return DimensionInterface[]
     */
    public function getDimensions(): array;

    /**
     * Get quantity of stuff moved to or from account
     */
    public function getQuantity(): Amount;

    /**
     * Get transaction signature
     */
    public function getSignature(): string;

    /**
     * Check if this is an added transaction
     */
    public function isAdded(): bool;

    /**
     * Check if this is a deleted transaction
     */
    public function isDeleted(): bool;

    /**
     * Get a simple string representation of transaction
     */
    public function __toString(): string;
}
