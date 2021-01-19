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

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\Account;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\InvalidSieFileException;
use Money\Money;

/**
 * Builder that creates and keeps track of account objects
 */
final class AccountBuilder
{
    private const ACCOUNT_TYPE_MAP = [
        'T' => AccountInterface::TYPE_ASSET,
        'S' => AccountInterface::TYPE_DEBT,
        'K' => AccountInterface::TYPE_COST,
        'I' => AccountInterface::TYPE_EARNING,
        '' => '',
    ];

    /** @var array<string, AccountInterface> */
    private array $accounts = [];

    /**
     * @param array<string, mixed> $attributes
     */
    public function defineAccount(
        string $id,
        string $description = '',
        string $type = '',
        ?Money $incomingBalance = null,
        array $attributes = [],
    ): void {
        if (!isset(self::ACCOUNT_TYPE_MAP[$type])) {
            throw new InvalidAccountException("Unknown type $type for account $id");
        }

        $oldAccount = $this->accounts[$id] ?? new Account($id);

        if ($oldAccount->getTransactions()) {
            throw new InvalidSieFileException("Unable to alter account definition once verifications has been created");
        }

        $newAccount = new Account(
            id: $oldAccount->getId(),
            description: $description ?: $oldAccount->getDescription(),
            type: self::ACCOUNT_TYPE_MAP[$type] ?: $oldAccount->getType(),
            attributes: $oldAccount->getAttributes(),
        );

        if (!$oldAccount->getSummary()->isEmpty()) {
            $newAccount->setIncomingBalance($oldAccount->getSummary()->getIncomingBalance());
        }

        if ($incomingBalance) {
            $newAccount->setIncomingBalance($incomingBalance);
        }

        foreach ($attributes as $key => $value) {
            $newAccount->setAttribute($key, $value);
        }

        $this->accounts[$id] = $newAccount;
    }

    public function getAccount(string $id): AccountInterface
    {
        if (!isset($this->accounts[$id])) {
            $this->defineAccount($id);
        }

        return $this->accounts[$id];
    }

    /**
     * @return array<AccountInterface>
     */
    public function getAccounts(): array
    {
        return array_values($this->accounts);
    }
}
