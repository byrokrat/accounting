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

namespace byrokrat\accounting;

/**
 * Basic implementation of an attributable object
 */
trait AttributableTrait
{
    /** @var array<string, mixed> */
    private $attributes = [];

    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[strtolower($name)] = $value;
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[strtolower($name)]);
    }

    public function getAttribute(string $name, mixed $default = ''): mixed
    {
        return $this->attributes[strtolower($name)] ?? $default;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
