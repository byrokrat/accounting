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
 * Copyright 2016-18 Hannes Forsgård
 */

namespace byrokrat\accounting\Interfaces\Traits;

use byrokrat\accounting\Interfaces\Dateable;
use byrokrat\accounting\Exception\LogicException;

/**
 * Basic implementation of the Dateable interface
 */
trait DateableTrait
{
    /**
     * @var \DateTimeInterface
     */
    private $date;

    public function setDate(\DateTimeInterface $date): Dateable
    {
        $this->date = $date;

        return $this;
    }

    public function hasDate(): bool
    {
        return isset($this->date);
    }

    public function getDate(): \DateTimeInterface
    {
        if (!$this->hasDate()) {
            throw new LogicException('Trying to get date when date is not set');
        }

        return $this->date;
    }
}
