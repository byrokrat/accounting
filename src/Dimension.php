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
 * Copyright 2016 Hannes Forsgård
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
     * @var int Dimension id number
     */
    private $number;

    /**
     * @var Dimension Parent dimension
     */
    private $parent;

    /**
     * Load values at construct
     *
     * @param int       $number      Dimension id number
     * @param string    $description Free text description
     * @param Dimension $parent      Optional parent dimension
     */
    public function __construct(int $number, string $description, Dimension $parent = null)
    {
        $this->number = $number;
        $this->setDescription($description);
        $this->parent = $parent;
    }

    /**
     * Get dimension id number
     */
    public function getNumber(): int
    {
        return $this->number;
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
     * @param  Dimension|int $dimension
     */
    public function inDimension($dimension): bool
    {
        if ($dimension instanceof Dimension) {
            $dimension = $dimension->getNumber();
        }

        if (!is_int($dimension)) {
            throw new Exception\LogicException(
                '$dimension must be an integer or a Dimension object, found: ' . gettype($dimension)
            );
        }

        if (!$this->hasParent()) {
            return false;
        }

        if ($this->getParent()->getNumber() === $dimension) {
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
