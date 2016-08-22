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

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\Exception;

/**
 * Helper that keeps track of parsing errors
 */
trait ErrorHelper
{
    /**
     * @var string[] List of unrecoverable runtime errors
     */
    private $errorMessages = [];

    /**
     * @var string[] List of recoverable runtime warnings
     */
    private $warningMessages = [];

    /**
     * @var integer Error reporting level
     */
    private $level = E_ERROR | E_WARNING;

    /**
     * Clear registered errors and warnings
     */
    public function resetErrorState()
    {
        $this->errorMessages = [];
        $this->warningMessages = [];
    }

    /**
     * Set error reporting level using E_ERROR and E_WARNING respectively
     *
     * If level is set to E_ERROR the exceptions are thrown on errors but not
     * on warnings. If level is set to 0 no exceptions are thrown. If error is
     * set to E_ERROR | E_WARNING (default) exceptions are thrown on both
     * errors and warnings.
     *
     * @param  int $level Desired error reporting level
     * @return void
     */
    public function setErrorLevel(int $level)
    {
        $this->level = $level;
    }

    /**
     * Check the list of parsing errors and throw exception if applicable
     *
     * @return void
     * @throws Exception\ParserException If parsing fails and error reporting is set
     */
    public function validateErrorState()
    {
        if ($this->level & E_ERROR && $this->getErrors() || $this->level & E_WARNING && $this->getWarnings()) {
            throw new Exception\ParserException($this->getErrors(), $this->getWarnings());
        }
    }

    /**
     * Get recorded errors
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errorMessages;
    }

    /**
     * Get recorded warnings
     *
     * @return string[]
     */
    public function getWarnings(): array
    {
        return $this->warningMessages;
    }

    /**
     * Called when an unknown row is encountered
     *
     * @param  string   $label Row label
     * @param  string[] $vars  Row variables
     * @return void
     */
    public function onUnknown(string $label, array $vars)
    {
        $this->registerWarning(
            array_reduce(
                $vars,
                function ($carry, $var) {
                    return "$carry \"$var\"";
                },
                "Encountered unknown statement: #$label"
            )
        );
    }

    /**
     * Register a runtime error
     *
     * @param  string $message A message describing the error
     * @return void
     */
    public function registerError(string $message)
    {
        $this->errorMessages[] = $message;
    }

    /**
     * Register a runtime warning
     *
     * @param  string $message A message describing the warning
     * @return void
     */
    public function registerWarning(string $message)
    {
        $this->warningMessages[] = $message;
    }
}
