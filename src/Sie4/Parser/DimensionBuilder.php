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
use byrokrat\accounting\Dimension\DimensionInterface;
use Money\Money;

/**
 * Builder that creates and keeps track of dimension objects
 */
final class DimensionBuilder
{
    /** @var array<string, Dimension> */
    private array $dimensions = [];

    /**
     * @param array<string, mixed> $attributes
     */
    public function defineDimension(
        string $id,
        string $parent = '',
        string $description = '',
        ?Money $incomingBalance = null,
        array $attributes = [],
    ): void {
        $dimension = $this->dimensions[$id] ?? new Dimension(id: $id, description: $description);

        // According to SIE rule 8.17 dimension 2 should by default be a subdimension to 1
        if ('2' == $id && !$parent) {
            $parent = '1';
        }

        if ($parent) {
            $parentDimension = $this->getDimension($parent);

            if (!in_array($dimension, $parentDimension->getChildren())) {
                $parentDimension->addChild($dimension);
            }
        }

        if ($incomingBalance) {
            $dimension->setIncomingBalance($incomingBalance);
        }

        foreach ($attributes as $key => $value) {
            $dimension->setAttribute($key, $value);
        }

        $this->dimensions[$id] = $dimension;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function defineObject(
        string $id,
        string $parent,
        string $description = '',
        ?Money $incomingBalance = null,
        array $attributes = [],
    ): void {
        $this->defineDimension(
            id: "$parent.$id",
            parent: $parent,
            description: $description,
            incomingBalance: $incomingBalance,
            attributes: $attributes,
        );
    }

    public function getDimension(string $id): Dimension
    {
        if (!isset($this->dimensions[$id])) {
            $this->defineDimension(
                id: $id,
                description: $this->getDefaultDimensionDescription($id),
            );
        }

        return $this->dimensions[$id];
    }

    public function getObject(string $id, string $parent): Dimension
    {
        if (!isset($this->dimensions["$parent.$id"])) {
            $this->defineObject($id, $parent);
        }

        return $this->dimensions["$parent.$id"];
    }

    /**
     * @return array<DimensionInterface>
     */
    public function getDimensions(): array
    {
        return array_values($this->dimensions);
    }

    /**
     * Default dimension descriptions according to SIE rule 8.17
     */
    private function getDefaultDimensionDescription(string $id): string
    {
        return match($id) {
            '1' => 'Kostnadsställe/resultatenhet',
            '2' => 'Kostnadsbärare',
            '6' => 'Projekt',
            '7' => 'Anställd',
            '8' => 'Kund',
            '9' => 'Leverantör',
            '10' => 'Faktura',
            default => ''
        };
    }
}
