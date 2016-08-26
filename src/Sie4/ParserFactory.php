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

use byrokrat\accounting\AccountFactory;
use Psr\Log\LogLevel;

/**
 * Simplifies the creation of parser objects
 */
class ParserFactory
{
    /**
     * Flag implying that all log events should be ignored
     */
    const IGNORE_ERRORS = '';

    /**
     * Flag implying that parsing should fail when an error occurs
     */
    const FAIL_ON_ERROR = LogLevel::ERROR;

    /**
     * Flag implying that parsing should fail when at least a warning occurs
     */
    const FAIL_ON_WARNING = LogLevel::WARNING;

    /**
     * Flag implying that parsing should fail when at least a notice occurs
     */
    const FAIL_ON_NOTICE = LogLevel::NOTICE;

    /**
     * Create a new parser
     *
     * @param string $logLevel Set when parsing should fail using one of the logging constants
     */
    public function createParser(string $logLevel = self::FAIL_ON_WARNING): Parser
    {
        $logger = new Logger;

        $logger->setLogLevel($logLevel);

        return new Parser(
            $logger,
            new AccountBuilder(new AccountFactory, $logger),
            new CurrencyBuilder($logger),
            new DimensionBuilder($logger),
            new VerificationBuilder($logger)
        );
    }
}
