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

namespace byrokrat\accounting\Exception;

/**
 * Exception thrown when a parsing action fails
 */
class ParserException extends RuntimeException
{
    /**
     * @var string[] Registered error messages
     */
    private $errors = [];

    /**
     * @var string[] Registered warning messages
     */
    private $warnings = [];

    /**
     * Load error and warning messages at construct
     *
     * @param string[] $errors
     * @param string[] $warnings
     */
    public function __construct(array $errors, array $warnings)
    {
        $this->errors = $errors;
        $this->warnings = $warnings;

        parent::__construct(
            sprintf(
                "Parsing failed due to the following issues: %s %s",
                $this->stringify('ERROR', $this->getErrors()),
                $this->stringify('WARNING', $this->getWarnings())
            )
        );
    }

    /**
     * Get registered errors
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get registered warnings
     *
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Create a nice string representation of list of messages
     */
    private function stringify(string $level, array $messages): string
    {
        return array_reduce(
            $messages,
            function (string $carry, string $message) use ($level) {
                return "$carry\n * [$level] $message";
            },
            ''
        );
    }
}
