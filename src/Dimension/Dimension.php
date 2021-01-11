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

use byrokrat\accounting\AttributableTrait;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Exception\RuntimeException;

class Dimension implements DimensionInterface
{
    use AttributableTrait;

    public function __construct(
        private string $id,
        private string $description = '',
        private ?DimensionInterface $parent = null
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    public function getParent(): DimensionInterface
    {
        if (!isset($this->parent)) {
            throw new RuntimeException(
                'Unable to read parent dimension, did you check if parent is set using hasParent()?'
            );
        }

        return $this->parent;
    }

    public function inDimension(DimensionInterface | string $dimension): bool
    {
        if ($dimension instanceof DimensionInterface) {
            $dimension = $dimension->getId();
        }

        if (!$this->hasParent()) {
            return false;
        }

        if ($this->getParent()->getId() === $dimension) {
            return true;
        }

        return $this->getParent()->inDimension($dimension);
    }

    public function getItems(): array
    {
        return array_filter([$this->parent]);
    }

    public function getSummary(): Summary
    {
        // TODO implement once we have transactions and children here
        return new Summary();
    }
}
