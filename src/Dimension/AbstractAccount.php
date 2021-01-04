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

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\Exception\RuntimeException;

abstract class AbstractAccount extends Dimension implements AccountInterface
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(string $number, string $description = '', array $attributes = [])
    {
        if (!is_numeric($number)) {
            throw new RuntimeException('Account number must be a numeric string');
        }

        parent::__construct($number, $description);

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    public function isAsset(): bool
    {
        return $this->getType() == self::TYPE_ASSET;
    }

    public function isCost(): bool
    {
        return $this->getType() == self::TYPE_COST;
    }

    public function isDebt(): bool
    {
        return $this->getType() == self::TYPE_DEBT;
    }

    public function isEarning(): bool
    {
        return $this->getType() == self::TYPE_EARNING;
    }
}
