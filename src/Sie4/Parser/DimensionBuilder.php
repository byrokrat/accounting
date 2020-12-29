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

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Dimension\Dimension;

/**
 * Builder that creates and manages accounting dimensions
 */
class DimensionBuilder
{
    private const RESERVED_DIMENSIONS_MAP = [
        '1' => 'Kostnadsställe/resultatenhet',
        '6' => 'Projekt',
        '7' => 'Anställd',
        '8' => 'Kund',
        '9' => 'Leverantör',
        '10' => 'Faktura'
    ];

    /** @var array<DimensionInterface> */
    private array $dimensions = [];

    public function __construct(
        private Logger $logger,
    ) {}

    public function addDimension(string $dimId, string $desc, string $super = ''): void
    {
        if (isset($this->dimensions[$dimId])) {
            $this->logger->log('warning', "Overwriting previously created dimension $dimId");
        }

        $this->dimensions[$dimId] = new Dimension(
            $dimId,
            $desc,
            $super ? $this->getDimension($super) : null
        );
    }

    public function addObject(string $super, string $objectId, string $desc): void
    {
        if (isset($this->dimensions["$super.$objectId"])) {
            $this->logger->log('warning', "Overwriting previously created object $super.$objectId");
        }

        $this->dimensions["$super.$objectId"] = new Dimension($objectId, $desc, $this->getDimension($super));
    }

    /**
     * Get dimension from internal store using number as key
     *
     * If dimension is not defined a new dimension is created, using one of the
     * reserved sie dimension descriptions if applicable.
     */
    public function getDimension(string $dimId): DimensionInterface
    {
        if (isset($this->dimensions[$dimId])) {
            return $this->dimensions[$dimId];
        }

        $this->logger->log('warning', "Dimension number $dimId not defined", 1);

        if ('2' === $dimId) {
            return $this->dimensions['2'] = new Dimension('2', 'Kostnadsbärare', $this->getDimension('1'));
        }

        $this->addDimension(
            $dimId,
            self::RESERVED_DIMENSIONS_MAP[$dimId] ?? 'UNSPECIFIED'
        );

        return $this->getDimension($dimId);
    }

    /**
     * Get accounting object from internal store using number and super as key
     */
    public function getObject(string $super, string $objectId): DimensionInterface
    {
        if (isset($this->dimensions["$super.$objectId"])) {
            return $this->dimensions["$super.$objectId"];
        }

        $this->logger->log('warning', "Object number $super.$objectId not defined", 1);

        $this->addObject($super, $objectId, 'UNSPECIFIED');

        return $this->getObject($super, $objectId);
    }

    /**
     * @return array<DimensionInterface>
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }
}
