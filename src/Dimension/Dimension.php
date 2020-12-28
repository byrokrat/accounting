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

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\AttributableTrait;
use byrokrat\accounting\Exception\LogicException;
use byrokrat\accounting\Query;

class Dimension implements DimensionInterface
{
    use AttributableTrait;

    /**
     * @var string
     */
    private $dimensionId;

    /**
     * @var string
     */
    private $description;

    /**
     * @var ?DimensionInterface
     */
    private $parent;

    public function __construct(string $dimensionId, string $description = '', DimensionInterface $parent = null)
    {
        $this->dimensionId = $dimensionId;
        $this->description = $description;
        $this->parent = $parent;
    }

    public function getId(): string
    {
        return $this->dimensionId;
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
            throw new LogicException(
                'Unable to read parent dimension, did you check if parent is set using hasParent()?'
            );
        }

        return $this->parent;
    }

    public function inDimension($dimension): bool
    {
        if ($dimension instanceof DimensionInterface) {
            $dimension = $dimension->getId();
        }

        if (!is_string($dimension)) {
            throw new LogicException(
                '$dimension must be a string or a dimension object, found: ' . gettype($dimension)
            );
        }

        if (!$this->hasParent()) {
            return false;
        }

        if ($this->getParent()->getId() === $dimension) {
            return true;
        }

        return $this->getParent()->inDimension($dimension);
    }

    public function select(): Query
    {
        return $this->hasParent() ? new Query([$this->getParent()]) : new Query();
    }
}
