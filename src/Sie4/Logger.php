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
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Internal error log
 */
class Logger extends AbstractLogger
{
    /**
     * @var array Definitions of under what reporting levels an event should be logged
     */
    private static $logLevelsMap = [
        LogLevel::EMERGENCY => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE,
            LogLevel::WARNING,   LogLevel::ERROR,     LogLevel::CRITICAL,
            LogLevel::ALERT,     LogLevel::EMERGENCY
        ],
        LogLevel::ALERT => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE,
            LogLevel::WARNING,   LogLevel::ERROR,     LogLevel::CRITICAL,
            LogLevel::ALERT
        ],
        LogLevel::CRITICAL => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE,
            LogLevel::WARNING,   LogLevel::ERROR,     LogLevel::CRITICAL
        ],
        LogLevel::ERROR => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE,
            LogLevel::WARNING,   LogLevel::ERROR
        ],
        LogLevel::WARNING => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE,
            LogLevel::WARNING
        ],
        LogLevel::NOTICE => [
            LogLevel::DEBUG,     LogLevel::INFO,      LogLevel::NOTICE
        ],
        LogLevel::INFO => [
            LogLevel::DEBUG,     LogLevel::INFO
        ],
        LogLevel::DEBUG => [
            LogLevel::DEBUG
        ],
    ];

    /**
     * @var array Logged messages
     */
    private $log = [];

    /**
     * @var string Error reporting level
     */
    private $level = LogLevel::WARNING;

    /**
     * @var integer Count lines for better error reporting
     */
    private $lineCount = 0;

    /**
     * [$lines description]
     * @var string[] The loaded lines of content logged events are related to
     */
    private $lines = [];

    /**
     * Clear log
     */
    public function resetLog(string $content = '')
    {
        $this->log = [];
        $this->lineCount = 0;
        $this->lines = explode("\n", $content);
    }

    /**
     * Set error reporting level using one of the LogLevel constants
     *
     * Defaults to LogLevel::WARNING
     *
     * @param  string $level Desired error reporting level
     * @return void
     * @see    \Psr\Log\LogLevel For the list of relevant log levels
     */
    public function setLogLevel(string $level)
    {
        $this->level = $level;
    }

    /**
     * Increment current line count
     */
    public function incrementLineCount()
    {
        $this->lineCount++;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        if (in_array($this->level, self::$logLevelsMap[$level])) {
            $this->log[] = $this->format($level, $message, $context);
        }
    }

    /**
     * Get logged messages
     */
    public function getLog(): array
    {
        return $this->log;
    }

    /**
     * Format a logged event
     */
    private function format(string $level, string $msg, array $context): string
    {
        return sprintf(
            '[%s] %s (%s: %s) %s',
            strtoupper($level),
            $msg,
            $this->lineCount,
            trim($this->lines[$this->lineCount - 1] ?? ''),
            json_encode((object)$context)
        );
    }
}
