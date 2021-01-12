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

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\AccountingObjectInterface;
use byrokrat\accounting\Transaction\TransactionInterface;

/**
 * The basic idea of a verification is a balanced collection of transactions
 */
interface VerificationInterface extends AccountingObjectInterface
{
    /**
     * Get date when transactions occured
     */
    public function getTransactionDate(): AccountingDate;

    /**
     * Get the date verification was entered into the registry
     */
    public function getRegistrationDate(): AccountingDate;

    /**
     * Get personal signature of accontant
     */
    public function getSignature(): string;

    /**
     * Get included transactions
     *
     * @return array<TransactionInterface>
     */
    public function getTransactions(): array;
}
