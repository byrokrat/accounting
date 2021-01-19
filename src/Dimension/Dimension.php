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

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\AbstractAccountingObject;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use Money\Money;

class Dimension extends AbstractAccountingObject implements DimensionInterface
{
    /** @var array<DimensionInterface> */
    private array $children = [];

    /** @var array<TransactionInterface> */
    private array $transactions = [];

    private Summary $summary;

    /**
     * @param array<DimensionInterface> $children
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        private string $id,
        private string $description = '',
        array $children = [],
        array $attributes = [],
    ) {
        parent::__construct($id, $description, $attributes);

        foreach ($children as $child) {
            $this->addChild($child);
        }

        $this->summary = new Summary();
    }

    public function addChild(DimensionInterface $child): void
    {
        $this->children[] = $child;
    }

    public function addTransaction(TransactionInterface $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function hasChildren(): bool
    {
        return !empty($this->children);
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getItems(): array
    {
        return $this->getChildren();
    }

    public function getSummary(): Summary
    {
        return array_reduce(
            [...$this->getTransactions(), ...$this->getChildren()],
            fn($summary, $item) => $summary->withSummary($item->getSummary()),
            $this->summary
        );
    }

    public function setIncomingBalance(Money $incomingBalance): void
    {
        $this->summary = Summary::fromIncomingBalance($incomingBalance);
    }
}
