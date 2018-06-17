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

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\QueryableInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Amount;

/**
 * The basic idea of a verification is a balanced collection of transactions
 */
interface VerificationInterface extends AttributableInterface, QueryableInterface
{
    /**
     * Get verification id number (may return 0 if verification does not contain a propper id)
     */
    public function getVerificationId(): int;

    /**
     * Get date when transactions occured
     */
    public function getTransactionDate(): \DateTimeImmutable;

    /**
     * Get the date verification was entered into the registry
     */
    public function getRegistrationDate(): \DateTimeImmutable;

    /**
     * Get freetext description
     */
    public function getDescription(): string;

    /**
     * Get personal signature of accontant
     */
    public function getSignature(): string;

    /**
     * Get included transactions
     *
     * @return TransactionInterface[]
     */
    public function getTransactions(): array;

    /**
     * Check if verification is balanced
     */
    public function isBalanced(): bool;

    /**
     * Get the sum of all positive transactions
     */
    public function getMagnitude(): Amount;
}
