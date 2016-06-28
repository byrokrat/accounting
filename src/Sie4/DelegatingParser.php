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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * Delegates found expressions to a registered consumer
 */
class DelegatingParser extends Grammar implements ConsumerInterface
{
    /**
     * @var ConsumerInterface
     */
    private $strategy;

    public function __construct(ConsumerInterface $strategy)
    {
        // TODO add a test that delegation works for all row types...
        $this->strategy = $strategy;
    }

    public function onUnknown(string $label, array $fields)
    {
        $this->strategy->onUnknown($label, $fields);
    }

    public function onAdress(string $kontakt, string $utdelningsadr, string $postadr, string $tel)
    {
        $this->strategy->onAdress($kontakt, $utdelningsadr, $postadr, $tel);
    }
}
