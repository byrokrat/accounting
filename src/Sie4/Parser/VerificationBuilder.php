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

use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\accounting\Verification\Verification;
use Psr\Log\LoggerInterface;

/**
 * Builder that creates and keeps track of verifications
 */
class VerificationBuilder
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Inject logger att construct
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Create a new transaction object
     *
     * @param string                 $series          The series verification should be a part of
     * @param string                 $number          Verification number
     * @param \DateTimeImmutable     $transactionDate Date of accounting action
     * @param string                 $desc            Free text description
     * @param \DateTimeImmutable     $regdate         Date of registration (defaults to $date)
     * @param string                 $sign            Signature
     * @param TransactionInterface[] $transactions    List of included transactions
     */
    public function createVerification(
        string $series,
        string $number,
        \DateTimeImmutable $transactionDate,
        string $desc = '',
        \DateTimeImmutable $regdate = null,
        string $sign = '',
        array $transactions = []
    ): VerificationInterface {
        $verification = new Verification(
            intval($number),
            $transactionDate,
            $regdate ?: $transactionDate,
            $desc,
            $sign,
            ...$transactions
        );

        $verification->setAttribute('series', $series);

        if ($transactions && !$verification->isBalanced()) {
            $this->logger->error('Trying to add an unbalanced verification');
        }

        return $verification;
    }
}
