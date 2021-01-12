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

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\SummaryEmptyException;
use byrokrat\accounting\Exception\SummaryNotBalancedException;
use Money\Money;

/**
 * Calculate amount summaries
 */
final class Summary
{
    private Money $incomingBalance;
    private Money $outgoingBalance;
    private Money $debit;
    private Money $credit;

    /** @var array<Money> */
    private array $amounts = [];

    /**
     * Create new summary with amount added
     */
    public static function fromAmount(Money $amount): self
    {
        return (new static())->withAmount($amount);
    }

    /**
     * Create new summary with incoming balance
     */
    public static function fromIncomingBalance(Money $amount): self
    {
        return (new static())->withIncomingBalance($amount);
    }

    /**
     * Check if summary is empty
     */
    public function isEmpty(): bool
    {
        return !isset($this->incomingBalance) && empty($this->amounts);
    }

    /**
     * Get incoming balance
     */
    public function getIncomingBalance(): Money
    {
        $this->calculateSummaries();
        return $this->incomingBalance;
    }

    /**
     * Get outgoing balance
     */
    public function getOutgoingBalance(): Money
    {
        $this->calculateSummaries();
        return $this->outgoingBalance;
    }

    /**
     * Get debit summary
     */
    public function getDebitTotal(): Money
    {
        $this->calculateSummaries();
        return $this->debit;
    }

    /**
     * Get credit summary
     */
    public function getCreditTotal(): Money
    {
        $this->calculateSummaries();
        return $this->credit;
    }

    /**
     * Check if summary is balanced
     */
    public function isBalanced(): bool
    {
        if ($this->isEmpty()) {
            return true;
        }

        return $this->getDebitTotal()->equals($this->getCreditTotal());
    }

    /**
     * Get collection magnitude (absolute value of debit or credit for balanced collections)
     *
     * @throws SummaryNotBalancedException if summary is not balanced
     */
    public function getMagnitude(): Money
    {
        if (!$this->isBalanced()) {
            throw new SummaryNotBalancedException('Unable to calculate magnitude of unbalanced collection');
        }

        return $this->getDebitTotal();
    }

    /**
     * Create a new summary from current with amount included
     */
    public function withAmount(Money $amount): self
    {
        $new = clone $this;
        $new->amounts[] = $amount;

        return $new;
    }

    /**
     * Create a new summary from current with incoming balance overwritten
     */
    public function withIncomingBalance(Money $incomingBalance): self
    {
        $new = clone $this;
        $new->incomingBalance = $incomingBalance;

        return $new;
    }

    /**
     * Create a new summary from current with values from summary added
     */
    public function withSummary(Summary $summary): self
    {
        $new = clone $this;

        if (isset($summary->incomingBalance)) {
            $new->incomingBalance = isset($new->incomingBalance)
                ? $new->incomingBalance->add($summary->incomingBalance)
                : $summary->incomingBalance;
        }

        $new->amounts = [...$new->amounts, ...$summary->amounts];

        return $new;
    }

    private function calculateSummaries(): void
    {
        // no need to re-calculate
        if (isset($this->outgoingBalance)) {
            return;
        }

        // create zero amount to preserve currency
        $zeroAmount = $this->createZeroAmount();

        // set incoming balance to zero if not specified
        if (!isset($this->incomingBalance)) {
            $this->incomingBalance = $zeroAmount;
        }

        // set start values
        $this->outgoingBalance = $this->incomingBalance;
        $this->debit = $zeroAmount;
        $this->credit = $zeroAmount;

        // calculate
        foreach ($this->amounts as $amount) {
            $this->outgoingBalance = $this->outgoingBalance->add($amount);

            if ($amount->isPositive()) {
                $this->debit = $this->debit->add($amount);
            } else {
                $this->credit = $this->credit->add($amount->absolute());
            }
        }
    }

    private function createZeroAmount(): Money
    {
        if (isset($this->incomingBalance)) {
            return $this->incomingBalance->subtract($this->incomingBalance);
        }

        if (count($this->amounts) == 0) {
            throw new SummaryEmptyException('Unable to access empty summary');
        }

        return $this->amounts[count($this->amounts) - 1]->subtract(
            $this->amounts[count($this->amounts) - 1]
        );
    }
}
