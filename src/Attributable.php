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

namespace byrokrat\accounting;

/**
 * Defines setAttribute and getAttribute
 */
interface Attributable
{
    /**
     * Register attribute
     *
     * @param  string $name  Case-insensitive name of attribute
     * @param  mixed  $value Value to register
     * @return void
     */
    public function setAttribute(string $name, $value);

    /**
     * Read registered attribute
     *
     * If attribute does not exist the empty string is returned.
     *
     * @param  string $name Case-insensitive name of attribute
     * @return mixed
     */
    public function getAttribute(string $name);
}
