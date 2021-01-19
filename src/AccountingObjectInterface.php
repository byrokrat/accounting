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

namespace byrokrat\accounting;

/**
 * Common interface for accounting objects
 */
interface AccountingObjectInterface
{
    /**
     * Get item identifier (note that uniqueness is not garantueed)
     */
    public function getId(): string;

    /**
     * Get item free text description
     */
    public function getDescription(): string;

    /**
     * Get items contained in this object
     *
     * @return array<AccountingObjectInterface>
     */
    public function getItems(): array;

    /**
     * Get summary for this item (including contained items when applicable)
     */
    public function getSummary(): Summary;

    /**
     * Register attribute
     */
    public function setAttribute(string $key, mixed $value): void;

    /**
     * Check if attribute has been set
     */
    public function hasAttribute(string $key): bool;

    /**
     * Read registered attribute
     */
    public function getAttribute(string $key): mixed;

    /**
     * Get the array of all registered attributes
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array;
}
