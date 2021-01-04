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

use byrokrat\accounting\Exception;

class Logger
{
    private int $lineCount = 0;

    /** @var array<string> */
    private array $lines = [];

    /** @var array<string> */
    private array $log = [];

    /**
     * @param string $content The content logged events are related to
     */
    public function resetLog(string $content = ''): void
    {
        $this->log = [];
        $this->lineCount = 0;
        $this->lines = explode("\n", $content);
    }

    /**
     * @return array<string>
     */
    public function getLog(): array
    {
        return $this->log;
    }

    public function incrementLineCount(): void
    {
        $this->lineCount++;
    }

    public function log(string $level, string $message, int $addToLineCount = 0): void
    {
        $lineCount = $this->lineCount + $addToLineCount;

        $this->log[] = sprintf(
            '[%s] %s (%s: %s)',
            strtoupper($level),
            $message,
            $lineCount,
            trim($this->lines[$lineCount - 1] ?? '')
        );
    }
}
