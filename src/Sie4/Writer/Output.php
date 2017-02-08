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
 * Copyright 2016-17 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Writer;

/**
 * Simple stream helper for creating content
 */
class Output
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
     * Escape and quote string for SIE output
     */
    public function escape(string $str): string
    {
        return sprintf(
            '"%s"',
            addslashes(
                preg_replace('/[[:cntrl:]]/', '', $str)
            )
        );
    }

    /**
     * Write to output
     */
    public function write(string $format, string ...$args)
    {
        $this->content .= sprintf(
            $format,
            ...array_map([$this, 'escape'], $args)
        );
    }

    /**
     * Write line to output
     */
    public function writeln(string $format, string ...$args)
    {
        $this->write($format . self::EOL, ...$args);
    }

    /**
     * Get content encoded using CP437
     */
    public function getContent(): string
    {
        return iconv("UTF-8", "CP437", $this->content);
    }
}
