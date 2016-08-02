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

namespace byrokrat\accounting\Sie4\Helper;

/**
 * Helper that keeps track of parsing errors
 */
trait ErrorHelper
{
    /**
     * @var string[] List of recoverable runtime error messages
     */
    private $errorMessages = [];

    /**
     * Called when a recoverable runtime error occurs
     *
     * @param  string $message A message describing the error
     * @return void
     */
    public function registerError(string $message)
    {
        $this->errorMessages[] = $message;
    }

    /**
     * Get recorded error messages
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errorMessages;
    }
}
