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
 * Copyright 2016-17 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

/**
 * Account value object containing a number and a description
 */
abstract class Account extends Dimension
{
    /**
     * Load account values at construct
     *
     * @param string $number      Numerical string identifying account
     * @param string $description Free text description of account
     * @param array  $attributes  Optional list of attributes
     */
    public function __construct(string $number, string $description = '', array $attributes = [])
    {
        if (!is_numeric($number)) {
            throw new Exception\RuntimeException('Account number must be a numeric string');
        }

        parent::__construct($number, $description);

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * Check if object represents an asset account
     */
    public function isAsset(): bool
    {
        return false;
    }

    /**
     * Check if object represents a cost account
     */
    public function isCost(): bool
    {
        return false;
    }

    /**
     * Check if object represents a debt account
     */
    public function isDebt(): bool
    {
        return false;
    }

    /**
     * Check if object represents an earnings account
     */
    public function isEarning(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getId(), $this->getDescription());
    }
}
