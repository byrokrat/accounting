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
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\accounting\Verification\Verification;
use byrokrat\amount\Currency;

class AbstractParser
{
    /**
     * @var AccountBuilder
     */
    private $accountBuilder;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CurrencyBuilder
     */
    private $currencyBuilder;

    /**
     * @var DimensionBuilder
     */
    private $dimensionBuilder;

    /**
     * @var array
     */
    protected $parsedItems = [];

    /**
     * @var array
     */
    protected $parsedAttributes = [];

    public function __construct(
        Logger $logger,
        AccountBuilder $accountBuilder,
        CurrencyBuilder $currencyBuilder,
        DimensionBuilder $dimensionBuilder
    ) {
        $this->logger = $logger;
        $this->accountBuilder = $accountBuilder;
        $this->currencyBuilder = $currencyBuilder;
        $this->dimensionBuilder = $dimensionBuilder;
    }

    protected function getAccountBuilder(): AccountBuilder
    {
        return $this->accountBuilder;
    }

    protected function getCurrencyBuilder(): CurrencyBuilder
    {
        return $this->currencyBuilder;
    }

    protected function getDimensionBuilder(): DimensionBuilder
    {
        return $this->dimensionBuilder;
    }

    protected function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @param string             $series          The series verification should be a part of
     * @param string             $number          Verification number
     * @param \DateTimeImmutable $transactionDate Date of accounting action
     * @param string             $desc            Free text description
     * @param \DateTimeImmutable $regdate         Date of registration (defaults to $date)
     * @param string             $sign            Signature
     * @param array              $transactionData Data to build transactions from
     */
    public function createVerification(
        string $series,
        string $number,
        \DateTimeImmutable $transactionDate,
        string $desc = '',
        \DateTimeImmutable $regdate = null,
        string $sign = '',
        array $transactionData = []
    ): VerificationInterface {
        $transactions = [];

        foreach ($transactionData as $data) {
            $transactions[] = new $data['type'](
                intval($number),
                $data['date'] ?: $transactionDate,
                $data['description'] ?: $desc,
                $data['signature'] ?: $sign,
                $data['amount'],
                $data['quantity'],
                $data['account'],
                ...$data['dimensions']
            );
        }

        $verification = new Verification(
            intval($number),
            $transactionDate,
            $regdate ?: $transactionDate,
            $desc,
            $sign,
            ...$transactions
        );

        $verification->setAttribute('series', $series);

        return $verification;
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
     */
    protected function assert($expr, string $failureMessage): bool
    {
        if ($expr) {
            return true;
        }

        $this->getLogger()->log('warning', $failureMessage);

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
        return $this->assert(is_object($expr) && $expr instanceof \DateTimeImmutable, $failureMessage);
    }
}
