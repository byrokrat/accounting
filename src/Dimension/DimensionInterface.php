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
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Exception\RuntimeException;

/**
 * A dimension is an entity through which transactions can be channeled
 */
interface DimensionInterface extends AccountingObjectInterface, AttributableInterface
{
    /**
     * Get free text description
     */
    public function getDescription(): string;

    /**
     * Check if dimension has a parent
     */
    public function hasParent(): bool;

    /**
     * Get dimension parent
     *
     * @throws RuntimeException If parent is not set
     */
    public function getParent(): DimensionInterface;

    /**
     * Check if this dimension is contained in $dimension
     */
    public function inDimension(DimensionInterface | string $dimension): bool;
}
