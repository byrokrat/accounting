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

namespace byrokrat\accounting\Dimension;

/**
 * Dimension representing a bookkeeping account
 */
interface AccountInterface extends DimensionInterface
{
    /**
     * Check if object represents an asset account
     */
    public function isAsset(): bool;

    /**
     * Check if object represents a cost account
     */
    public function isCost(): bool;

    /**
     * Check if object represents a debt account
     */
    public function isDebt(): bool;

    /**
     * Check if object represents an earnings account
     */
    public function isEarning(): bool;

    /**
     * Convert account to string
     */
    public function __toString(): string;
}
