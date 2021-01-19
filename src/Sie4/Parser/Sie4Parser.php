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

use byrokrat\accounting\Container;
use byrokrat\accounting\Exception\InvalidSieFileException;
use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\Sie4\SieMetaData;

/**
 * Sie4 compliant parser
 *
 * Note that content should be passed in the PC8 charset (code page 437)
 */
final class Sie4Parser implements ParserAttributes
{
    private Grammar $grammar;

    public function __construct()
    {
        $this->grammar = new Grammar();
    }

    public function parse(string $content): Container
    {
        try {
            $content = (string)preg_replace(
                '/[\xFF]/',
                ' ',
                (string)iconv('CP437', 'UTF-8//IGNORE', $content)
            );

            return new Container(
                ...$this->grammar->parse($content),
                // @phpstan-ignore-next-line
                ...$this->grammar->accounts->getAccounts(),
                // @phpstan-ignore-next-line
                ...$this->grammar->dimensions->getDimensions(),
            );
        } catch (\Exception $exception) {
            throw new InvalidSieFileException($exception->getMessage(), 0, $exception);
        }
    }

    public function getParsedMetaData(): SieMetaData
    {
        // @phpstan-ignore-next-line
        return $this->grammar->meta ?? throw new RuntimeException('Unable to access meta data, nothing parsed');
    }
}
