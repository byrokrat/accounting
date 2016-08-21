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

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\Dimension;

/**
 * Helper that creates and manages accounting dimensions
 */
trait DimensionHelper
{
    /**
     * @var array Map of reserved dimension numbers to descriptions
     */
    private static $reservedDimsMap = [
        1 => 'Kostnadsställe/resultatenhet',
        6 => 'Projekt',
        7 => 'Anställd',
        8 => 'Kund',
        9 => 'Leverantör',
        10 => 'Faktura'
    ];

    /**
     * @var Dimension[] Created dimensions
     */
    private $dims = [];

    /**
     * Called when a recoverable runtime error occurs
     */
    abstract public function registerError(string $message);

    /**
     * Called when a #DIM post is encountered
     */
    public function onDim(int $number, string $desc): Dimension
    {
        return $this->dims[$number] = new Dimension($number, $desc);
    }

    /**
     * Called when a #UNDERDIM post is encountered
     */
    public function onUnderdim(int $number, string $desc, int $super): Dimension
    {
        return $this->dims[$number] = new Dimension($number, $desc, $this->getDimension($super));
    }

    /**
     * Called when a #OBJEKT post is encountered
     */
    public function onObjekt(int $super, int $number, string $desc): Dimension
    {
        return $this->dims["$super.$number"] = new Dimension($number, $desc, $this->getDimension($super));
    }

    /**
     * Get dimension from internal store using number as key
     *
     * If dimension is not defined a new dimension is created, using one of the
     * reserved sie dimension description if applicable.
     */
    public function getDimension(int $number): Dimension
    {
        if (isset($this->dims[$number])) {
            return $this->dims[$number];
        }

        $this->registerError("Dimension number $number not defined");

        if (2 === $number) {
            return $this->dims[2] = new Dimension(2, 'Kostnadsbärare', $this->getDimension(1));
        }

        return $this->onDim(
            $number,
            self::$reservedDimsMap[$number] ?? 'UNSPECIFIED'
        );
    }

    /**
     * Get dimension from internal store using number and super as key
     */
    public function getObject(int $super, int $number): Dimension
    {
        if (isset($this->dims["$super.$number"])) {
            return $this->dims["$super.$number"];
        }

        $this->registerError("Object number $super.$number not defined");

        return $this->onObjekt($super, $number, 'UNSPECIFIED');
    }
}
