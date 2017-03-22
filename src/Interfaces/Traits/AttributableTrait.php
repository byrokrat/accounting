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

namespace byrokrat\accounting\Interfaces\Traits;

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Exception\LogicException;

/**
 * Basic implementation of the Attributable interface
 */
trait AttributableTrait
{
    /**
     * @var array Registered attributes
     */
    private $attributes = [];

    public function setAttribute(string $name, $value): Attributable
    {
        $this->attributes[strtolower($name)] = $value;

        return $this;
    }

    public function hasAttribute(string $name): bool
    {
        return isset($this->attributes[strtolower($name)]);
    }

    public function &getAttribute(string $name)
    {
        if (!$this->hasAttribute($name)) {
            throw new LogicException("Trying to read non-existing attribute $name");
        }

        return $this->attributes[strtolower($name)];
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
