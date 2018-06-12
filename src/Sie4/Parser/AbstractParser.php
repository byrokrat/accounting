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
 * Copyright 2016-18 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Container;
use byrokrat\accounting\Processor;
use byrokrat\amount\Currency;

/**
 * Manage parser dependencies
 */
class AbstractParser
{
    /**
     * @var AccountBuilder Builder for account objects
     */
    private $accountBuilder;

    /**
     * @var Container Container for parsed data
     */
    private $container;

    /**
     * @var Logger Internal error log
     */
    private $logger;

    /**
     * @var CurrencyBuilder Builder for monetary objects
     */
    private $currencyBuilder;

    /**
     * @var DimensionBuilder Builder for dimension objects
     */
    private $dimensionBuilder;

    /**
     * @var VerificationBuilder Builder for verificationi objects
     */
    private $verificationBuilder;

    /**
     * @var Processor Summarize transactions after parse
     */
    private $processor;

    /**
     * Inject dependencies at construct
     */
    public function __construct(
        Logger $logger,
        AccountBuilder $accountBuilder,
        CurrencyBuilder $currencyBuilder,
        DimensionBuilder $dimensionBuilder,
        VerificationBuilder $verificationBuilder,
        Processor $processor
    ) {
        $this->logger = $logger;
        $this->accountBuilder = $accountBuilder;
        $this->currencyBuilder = $currencyBuilder;
        $this->dimensionBuilder = $dimensionBuilder;
        $this->verificationBuilder = $verificationBuilder;
        $this->processor = $processor;
        $this->resetContainer();
    }

    /**
     * Get container with parsed data
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Reset container to empty state
     */
    protected function resetContainer()
    {
        $this->container = new Container;
    }

    /**
     * Get builder of account objects
     */
    protected function getAccountBuilder(): AccountBuilder
    {
        return $this->accountBuilder;
    }

    /**
     * Get builder of monetary objects
     */
    protected function getCurrencyBuilder(): CurrencyBuilder
    {
        return $this->currencyBuilder;
    }

    /**
     * Get builder of dimension objects
     */
    protected function getDimensionBuilder(): DimensionBuilder
    {
        return $this->dimensionBuilder;
    }

    /**
     * Get internal error log
     */
    protected function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * Get builder of verification objects
     */
    protected function getVerificationBuilder(): VerificationBuilder
    {
        return $this->verificationBuilder;
    }

    /**
     * Get transaction processor
     */
    protected function getProcessor(): Processor
    {
        return $this->processor;
    }

    /**
     * Helper that writes an attribute to an attributable
     */
    protected function writeAttribute(AttributableInterface $attributable, string $key, $value, string $year = '')
    {
        if ('' == $year || '0' == $year) {
            $attributable->setAttribute($key, $value);
        }

        if ('' != $year) {
            $attributable->setAttribute("{$key}[$year]", $value);
        }
    }

    /**
     * Assert that $expr is thruthy and log a warning if not
     *
     * @param  mixed  $expr
     * @param  string $failureMessage
     * @return bool   The thruthiness of $expr
     */
    protected function assert($expr, string $failureMessage): bool
    {
        if ($expr) {
            return true;
        }

        $this->getLogger()->warning($failureMessage);

        return false;
    }

    /**
     * Assert that $expr is an array and log warning if not
     */
    protected function assertArray($expr, $failureMessage = 'Expected a set of values'): bool
    {
        return $this->assert(is_array($expr), $failureMessage);
    }

    /**
     * Assert that $expr is a boolen and log warning if not
     */
    protected function assertBool($expr, $failureMessage = 'Expected bool (1 or 0)'): bool
    {
        return $this->assert(is_bool($expr), $failureMessage);
    }

    /**
     * Assert that $expr is an integer and log warning if not
     */
    protected function assertInt($expr, $failureMessage = 'Expected integer'): bool
    {
        return $this->assert(is_int($expr), $failureMessage);
    }

    /**
     * Assert that $expr is a string and log warning if not
     */
    protected function assertString($expr, $failureMessage = 'Expected string'): bool
    {
        return $this->assert(is_string($expr), $failureMessage);
    }

    /**
     * Assert that $expr is an account and log warning if not
     */
    protected function assertAccount($expr, $failureMessage = 'Expected account'): bool
    {
        return $this->assert(is_object($expr) && $expr instanceof AccountInterface, $failureMessage);
    }

    /**
     * Assert that $expr is a monetary amount and log warning if not
     */
    protected function assertAmount($expr, $failureMessage = 'Expected monetary amount'): bool
    {
        return $this->assert(is_object($expr) && $expr instanceof Currency, $failureMessage);
    }

    /**
     * Assert that $expr is a Date and log warning if not
     */
    protected function assertDate($expr, $failureMessage = 'Expected date'): bool
    {
        return $this->assert(is_object($expr) && $expr instanceof \DateTimeInterface, $failureMessage);
    }
}
