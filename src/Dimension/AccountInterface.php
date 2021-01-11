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

/**
 * Dimension representing a bookkeeping account
 */
interface AccountInterface extends DimensionInterface
{
    public const TYPE_ASSET = 'ASSET';
    public const TYPE_COST = 'COST';
    public const TYPE_DEBT = 'DEBT';
    public const TYPE_EARNING = 'EARNING';

    /**
     * Get account type identifier
     */
    public function getType(): string;

    /**
     * Check if object represents a balance account (asset or debt)
     */
    public function isBalanceAccount(): bool;

    /**
     * Check if object represents a result account (earning or cost)
     */
    public function isResultAccount(): bool;

    /**
     * Check if object represents an asset account
     */
    public function isAssetAccount(): bool;

    /**
     * Check if object represents a cost account
     */
    public function isCostAccount(): bool;

    /**
     * Check if object represents a debt account
     */
    public function isDebtAccount(): bool;

    /**
     * Check if object represents an earnings account
     */
    public function isEarningAccount(): bool;
}
