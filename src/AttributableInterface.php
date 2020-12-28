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

namespace byrokrat\accounting;

/**
 * Defines methods for reading and writing attributes
 *
 * NOTE that keys should be case-insensitive.
 */
interface AttributableInterface
{
    /**
     * Register attribute
     *
     * @param mixed $value
     */
    public function setAttribute(string $key, $value): void;

    /**
     * Check if attribute has been set
     */
    public function hasAttribute(string $key): bool;

    /**
     * Read registered attribute
     *
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute(string $key, $default = '');

    /**
     * Get the array of all registered attributes
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array;
}
