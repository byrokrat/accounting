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

use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\InvalidArgumentException;

final class Account extends Dimension implements AccountInterface
{
    private const VALID_TYPES = [self::TYPE_ASSET, self::TYPE_COST, self::TYPE_DEBT, self::TYPE_EARNING];

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        string $id,
        string $description = '',
        private string $type = '',
        array $attributes = [],
    ) {
        if (!ctype_digit($id)) {
            throw new InvalidAccountException('Account id must be a numeric string');
        }

        $this->type = $this->type ?: $this->inferType((int)$id);

        if (!in_array($this->type, self::VALID_TYPES)) {
            throw new InvalidAccountException("Invalid account type {$this->type}, use one of the type constants");
        }

        parent::__construct($id, $description);

        foreach ($attributes as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidArgumentException('Attribute key must be string');
            }

            $this->setAttribute($key, $value);
        }
    }

    public function getType(): string
    {
        return $this->type;
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

    private function inferType(int $id): string
    {
        return match (true) {
            $id < 2000 => self::TYPE_ASSET,
            $id < 3000 => self::TYPE_DEBT,
            $id < 4000 => self::TYPE_EARNING,
            default => self::TYPE_COST,
        };
    }
}
