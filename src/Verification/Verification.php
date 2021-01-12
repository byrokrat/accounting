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

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AttributableTrait;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidVerificationException;
use byrokrat\accounting\Exception\UnbalancedVerificationException;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Exception as AmountException;

/**
 * Verification value object wrapping a set of transactions
 */
final class Verification implements VerificationInterface
{
    use AttributableTrait;

    private Summary $summary;
    private \DateTimeImmutable $transactionDate;
    private \DateTimeImmutable $registrationDate;

    /**
     * @param array<TransactionInterface> $transactions
     * @param array<string, string> $attributes
     * @throws InvalidVerificationException If data is invalid
     * @throws UnbalancedVerificationException If verification is not balanced
     */
    public function __construct(
        private string $id = '',
        ?\DateTimeImmutable $transactionDate = null,
        ?\DateTimeImmutable $registrationDate = null,
        private string $description = '',
        private string $signature = '',
        private array $transactions = [],
        array $attributes = [],
    ) {
        if (!empty($this->id) && !ctype_digit($this->id)) {
            throw new InvalidVerificationException('Verification id must be a numeric string');
        }

        // @TODO should be a NullDate implementation? AccountingDate::today()??
        $this->transactionDate = $transactionDate ?: new \DateTimeImmutable();

        $this->registrationDate = $registrationDate ?: $this->transactionDate;

        $this->summary = new Summary();

        try {
            foreach ($this->transactions as $transaction) {
                if (!$transaction instanceof TransactionInterface) {
                    throw new InvalidArgumentException('Transaction must implement TransactionInterface');
                }

                $this->summary = $this->summary->withSummary($transaction->getSummary());
            }

            if (!$this->summary->isBalanced()) {
                throw new UnbalancedVerificationException('Unable to create unbalanced verification');
            }
        } catch (AmountException $exception) {
            throw new InvalidVerificationException($exception->getMessage(), 0, $exception);
        }

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTransactionDate(): \DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function getRegistrationDate(): \DateTimeImmutable
    {
        return $this->registrationDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function getItems(): array
    {
        return $this->getTransactions();
    }
}
