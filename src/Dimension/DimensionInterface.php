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

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\AccountingObjectInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Amount;

/**
 * A dimension is an entity through which transactions can be channeled
 */
interface DimensionInterface extends AccountingObjectInterface
{
    /**
     * Check if dimension has children
     */
    public function hasChildren(): bool;

    /**
     * Get child dimensions
     *
     * @return array<DimensionInterface>
     */
    public function getChildren(): array;

    /**
     * Link transaction with dimension
     *
     * @internal Note that this method is used for setting up cross references from
     *     transactions back to dimensions and should not be called from user land.
     */
    public function addTransaction(TransactionInterface $transaction): void;

    /**
     * Get linked transactions
     *
     * @return array<TransactionInterface>
     */
    public function getTransactions(): array;

    /**
     * Set incoming balance
     */
    public function setIncomingBalance(Amount $incomingBalance): void;
}
