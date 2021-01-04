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
use byrokrat\accounting\Exception\LogicException;
use byrokrat\accounting\Query;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Amount;

/**
 * Verification value object wrapping a list of transactions
 */
final class Verification implements VerificationInterface
{
    use AttributableTrait;

    private Summary $summary;

    /**
     * @TODO Add sensible defaults
     * @param array<TransactionInterface> $transactions
     * @param array<string, string> $attributes
     */
    public function __construct(
        private int $id,
        private \DateTimeImmutable $transactionDate,
        private \DateTimeImmutable $registrationDate,
        private string $description,
        private string $signature,
        private array $transactions,
        array $attributes = [],
    ) {
        $this->summary = new Summary();

        foreach ($this->transactions as $transaction) {
            if (!$transaction instanceof TransactionInterface) {
                throw new LogicException('TypeError: transaction must implement TransactionInterface');
            }

            // Validate currency
            $this->summary->addAmount(
                $transaction->getAmount()->subtract($transaction->getAmount())
            );

            // Add amount to summary
            if (!$transaction->isDeleted()) {
                $this->summary->addAmount($transaction->getAmount());
            }
        }

        foreach ($attributes as $key => $value) {
            if (!is_string($key)) {
                throw new LogicException('TypeError: attribute key must be string');
            }

            if (!is_string($value)) {
                throw new LogicException('TypeError: attribute value must be string');
            }

            $this->setAttribute($key, $value);
        }
    }

    public function getVerificationId(): int
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

    public function isBalanced(): bool
    {
        return $this->summary->isBalanced();
    }

    public function getMagnitude(): Amount
    {
        return $this->summary->getMagnitude();
    }

    public function select(): Query
    {
        return new Query($this->getTransactions());
    }
}
