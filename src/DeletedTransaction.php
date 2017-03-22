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

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

/**
 * Represents a transaction that has been deleted from an existing verification
 */
class DeletedTransaction extends Transaction
{
    public function __construct(
        Account $account,
        Amount $amount,
        string $signature,
        Amount $quantity = null,
        Dimension ...$dimensions
    ) {
        parent::__construct($account, $amount, $quantity, ...$dimensions);
        $this->setSignature($signature);
    }

    public function __toString(): string
    {
        return '(DELETED) ' . parent::__toString();
    }

    public function isDeleted(): bool
    {
        return true;
    }
}
