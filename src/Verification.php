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

namespace byrokrat\accounting;

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Interfaces\Dateable;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Signable;
use byrokrat\accounting\Interfaces\Queryable;
use byrokrat\accounting\Interfaces\Traits\AttributableTrait;
use byrokrat\accounting\Interfaces\Traits\DateableTrait;
use byrokrat\accounting\Interfaces\Traits\DescribableTrait;
use byrokrat\accounting\Interfaces\Traits\SignableTrait;
use byrokrat\amount\Amount;

/**
 * Simple verification value object wrapping a list of transactions
 */
class Verification implements Attributable, Dateable, Describable, Queryable, Signable, \IteratorAggregate
{
    use AttributableTrait, DateableTrait, DescribableTrait, SignableTrait;

    /**
     * @var int Verification id number
     */
    private $idNumber = 0;

    /**
     * @var \DateTimeInterface Date verification was entered inte the registry
     */
    private $registrationDate;

    /**
     * @var Summary Transaction summaries
     */
    private $summary;

    /**
     * @var Transaction[] Included transactions
     */
    private $transactions = [];

    /**
     * Optionally load dependencies at construct
     */
    public function __construct(Summary $summary = null)
    {
        $this->setDate(new \DateTimeImmutable);
        $this->summary = $summary ?: new Summary;
    }

    /**
     * Set verification id number
     */
    public function setId(int $idNumber): self
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Check if a verification id number is set
     */
    public function hasId(): bool
    {
        return $this->idNumber > 0;
    }

    /**
     * Get verification id number
     */
    public function getId(): int
    {
        return $this->idNumber;
    }

    /**
     * Set the date verification was entered inte the registry
     */
    public function setRegistrationDate(\DateTimeInterface $date): self
    {
        $this->registrationDate = $date;

        return $this;
    }

    /**
     * Get the date verification was entered inte the registry
     *
     * If no registration date is set the regular verification date is returned.
     */
    public function getRegistrationDate(): \DateTimeInterface
    {
        return $this->registrationDate ?: $this->getDate();
    }

    /**
     * Add a transaction to verification
     */
    public function addTransaction(Transaction $transaction): self
    {
        $transaction->setAttribute('ver_num', $this->getId());
        $this->transactions[] = $transaction;

        $this->summary->addAmount(
            $transaction->getAmount()->subtract($transaction->getAmount())
        );

        if (!$transaction->isDeleted()) {
            $this->summary->addAmount($transaction->getAmount());
        }

        return $this;
    }

    /**
     * Get included transactions
     *
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Implements the IteratorAggregate interface
     */
    public function getIterator(): \Generator
    {
        foreach ($this->getTransactions() as $transaction) {
            yield $transaction;
        }
    }

    /**
     * For verifications queryable content consists of transactions
     */
    public function select(): Query
    {
        return new Query($this->getIterator());
    }

    /**
     * Check if verification is balanced
     */
    public function isBalanced(): bool
    {
        return $this->summary->isBalanced();
    }

    /**
     * Get the sum of all positive transactions
     */
    public function getMagnitude(): Amount
    {
        return $this->summary->getMagnitude();
    }

    /**
     * Generate a simple string representation of transaction
     */
    public function __toString(): string
    {
        $delim = "\n * ";

        return sprintf(
            "[%s] %s%s%s",
            $this->getDate()->format('Ymd'),
            $this->getDescription(),
            $delim,
            implode($delim, $this->getTransactions())
        );
    }
}
