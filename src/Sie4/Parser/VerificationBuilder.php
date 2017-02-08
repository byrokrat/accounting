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

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Account;
use byrokrat\accounting\Dimension;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Verification;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency;
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
     * @param  Account            $account    Account this transaction concerns
     * @param  Dimension[]        $dimensions Other dimensions this transaction concerns
     * @param  Currency           $amount     Transacted amounts
     * @param  \DateTimeInterface $date       Date of accounting action
     * @param  string             $desc       Free text description
     * @param  Amount             $quantity   Quantity if defined
     * @param  string             $sign       Signature
     */
    public function createTransaction(
        Account $account,
        array $dimensions,
        Currency $amount,
        \DateTimeInterface $date = null,
        string $desc = '',
        Amount $quantity = null,
        string $sign = ''
    ): Transaction {
        $transaction = new Transaction($account, $amount, $quantity, ...$dimensions);

        if ($date) {
            $transaction->setDate($date);
        }

        if ($desc) {
            $transaction->setDescription($desc);
        }

        if ($sign) {
            $transaction->setSignature($sign);
        }

        return $transaction;
    }

    /**
     * Create a new transaction object
     *
     * @param string             $series       The series verification should be a part of
     * @param string             $number       Verification number
     * @param \DateTimeInterface $date         Date of accounting action
     * @param string             $desc         Free text description
     * @param \DateTimeInterface $regdate      Date of registration (defaults to $date)
     * @param string             $sign         Signature
     * @param Transaction[]      $transactions List of included transactions
     */
    public function createVerification(
        string $series,
        string $number,
        \DateTimeInterface $date,
        string $desc = '',
        \DateTimeInterface $regdate = null,
        string $sign = '',
        array $transactions = []
    ): Verification {
        $verification = (new Verification)->setAttribute('series', $series)->setDate($date);

        if ($number) {
            $verification->setNumber(intval($number));
        }

        if ($desc) {
            $verification->setDescription($desc);
        }

        if ($regdate) {
            $verification->setRegistrationDate($regdate);
        }

        if ($sign) {
            $verification->setSignature($sign);
        }

        foreach ($transactions as $transaction) {
            if (!$transaction->hasDate()) {
                $transaction->setDate($verification->getDate());
            }

            if (!$transaction->getDescription()) {
                $transaction->setDescription($verification->getDescription());
            }

            $verification->addTransaction($transaction);
        }

        if (!$verification->getTransactions()) {
            $this->logger->warning('Trying to add a verification without transactions');
        }

        if ($verification->getTransactions() && !$verification->isBalanced()) {
            $this->logger->error('Trying to add an unbalanced verification');
        }

        return $verification;
    }
}
