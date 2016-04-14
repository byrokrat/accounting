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

declare(strict_types=1);

namespace byrokrat\accounting\Sie;

/**
 * Simple stream helper for creating content
 */
class Stream
{
    /**
     * End of line chars used
     */
    const EOL = "\r\n";

    /**
     * @var string Written content
     */
    private $content = '';

    /**
     * Write to stream
     */
    public function write(string $str)
    {
        $this->content .= $str;
    }

    /**
     * Write line to stream
     */
    public function writeln(string $str)
    {
        $this->write($str . self::EOL);
    }

    /**
     * Get stream content encoded using CP437
     */
    public function getContent(): string
    {
        return iconv("UTF-8", "CP437", $this->content);
    }
}
