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

namespace byrokrat\accounting;

/**
 * A container is a queryable and attributable keeper of bookkeeping objects
 *
 * @implements \IteratorAggregate<mixed>
 */
class Container implements AttributableInterface, QueryableInterface, \IteratorAggregate
{
    use AttributableTrait;

    /** @var array<mixed> */
    private $items;

    /**
     * @param array<mixed> ...$items
     */
    public function __construct(...$items)
    {
        $this->items = $items;
    }

    /**
     * @return array<mixed>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return iterable<mixed>
     */
    public function getIterator(): iterable
    {
        yield from $this->getItems();
    }

    public function select(): Query
    {
        return new Query($this->getItems());
    }
}
