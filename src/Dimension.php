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

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Queryable;
use byrokrat\accounting\Interfaces\Traits\AttributableTrait;
use byrokrat\accounting\Interfaces\Traits\DescribableTrait;

/**
 * Defines an entity through which transactions can be channeled
 */
class Dimension implements Attributable, Describable, Queryable
{
    use AttributableTrait, DescribableTrait;

    /**
     * @var string Dimension identification
     */
    private $dimensionId;

    /**
     * @var Dimension Parent dimension
     */
    private $parent;

    /**
     * Load values at construct
     *
     * @param string    $dimensionId Dimension identification
     * @param string    $description Free text description
     * @param Dimension $parent      Optional parent dimension
     */
    public function __construct(string $dimensionId, string $description = '', Dimension $parent = null)
    {
        $this->dimensionId = $dimensionId;
        $this->setDescription($description);
        $this->parent = $parent;
    }

    /**
     * Get dimension identification
     */
    public function getId(): string
    {
        return $this->dimensionId;
    }

    /**
     * Check if dimension has a parent
     */
    public function hasParent(): bool
    {
        return isset($this->parent);
    }

    /**
     * Get dimension parent
     *
     * @throws Exception\LogicException If parent is not set
     */
    public function getParent(): Dimension
    {
        if (!$this->hasParent()) {
            throw new Exception\LogicException(
                'Unable to read parent dimension, did you check if parent is set using hasParent()?'
            );
        }

        return $this->parent;
    }

    /**
     * Check if this dimension is contained in $dimension
     *
     * @param  Dimension|string $dimension
     */
    public function inDimension($dimension): bool
    {
        if ($dimension instanceof Dimension) {
            $dimension = $dimension->getId();
        }

        if (!is_string($dimension)) {
            throw new Exception\LogicException(
                '$dimension must be a string or a Dimension object, found: ' . gettype($dimension)
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

    /**
     * Implements the Queryable interface
     */
    public function query(): Query
    {
        return $this->hasParent() ? new Query([$this->getParent()]) : new Query;
    }
}
