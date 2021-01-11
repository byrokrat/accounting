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

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\Dimension;

/**
 * Builder that creates and manages accounting dimensions
 *
 * @TODO If we use templates then maybe Dimension::addChild is not needed anymore
 */
final class DimensionBuilder
{
    private const UNSPECIFIED = 'UNSPECIFIED';

    /** @var array<Dimension> */
    private array $dimensions = [];

    public function __construct(
        private Logger $logger,
    ) {}

    public function addDimension(string $dimId, string $desc = self::UNSPECIFIED, string $parentId = ''): void
    {
        if (isset($this->dimensions[$dimId])) {
            $this->logger->log('warning', "Overwriting previously created dimension $dimId");
        }

        $this->dimensions[$dimId] = new Dimension($dimId, $desc);

        if ($parentId) {
            $this->getDimension($parentId)->addChild($this->dimensions[$dimId]);
        }
    }

    public function addObject(string $parentId, string $objectId, string $desc = self::UNSPECIFIED): void
    {
        if (isset($this->dimensions["$parentId.$objectId"])) {
            $this->logger->log('warning', "Overwriting previously created object $parentId.$objectId");
        }

        $this->dimensions["$parentId.$objectId"] = new Dimension($objectId, $desc);

        $this->getDimension($parentId)->addChild($this->dimensions["$parentId.$objectId"]);
    }

    /**
     * Get dimension from internal store using number as key
     *
     * If dimension is not defined a new dimension is created, using one of the
     * reserved sie dimension descriptions if applicable.
     */
    public function getDimension(string $dimId): Dimension
    {
        if (isset($this->dimensions[$dimId])) {
            return $this->dimensions[$dimId];
        }

        $this->logger->log('warning', "Dimension number $dimId not defined", 1);

        $this->addDimension(
            $dimId,
            $this->getReservedDimensionDesc($dimId)
        );

        $dimension =  $this->getDimension($dimId);

        if ('2' === $dimId) {
            $this->getDimension('1')->addChild($dimension);
        }

        return $dimension;
    }

    /**
     * Get accounting object from internal store using number and super as key
     */
    public function getObject(string $parentId, string $objectId): Dimension
    {
        if (isset($this->dimensions["$parentId.$objectId"])) {
            return $this->dimensions["$parentId.$objectId"];
        }

        $this->logger->log('warning', "Object number $parentId.$objectId not defined", 1);

        $this->addObject($parentId, $objectId);

        return $this->getObject($parentId, $objectId);
    }

    /**
     * @return array<Dimension>
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    private function getReservedDimensionDesc(string $dimId): string
    {
        return match($dimId) {
            '1' => 'Kostnadsställe/resultatenhet',
            '2' => 'Kostnadsbärare',
            '6' => 'Projekt',
            '7' => 'Anställd',
            '8' => 'Kund',
            '9' => 'Leverantör',
            '10' => 'Faktura',
            default => self::UNSPECIFIED
        };
    }
}
