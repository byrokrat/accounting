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

use byrokrat\accounting\Dimension\AccountFactory;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\AssetAccount;
use byrokrat\accounting\Dimension\DebtAccount;
use byrokrat\accounting\Dimension\CostAccount;
use byrokrat\accounting\Dimension\EarningAccount;
use byrokrat\accounting\Exception;

/**
 * Builder that creates and keeps track of account objects
 */
final class AccountBuilder
{
    private const ACCOUNT_TYPE_MAP = [
        'T' => AssetAccount::class,
        'S' => DebtAccount::class,
        'K' => CostAccount::class,
        'I' => EarningAccount::class
    ];

    /** @var array<AccountInterface> */
    private array $accounts = [];

    public function __construct(
        private AccountFactory $factory,
        private Logger $logger,
    ) {}

    public function addAccount(string $number, string $description): void
    {
        if (isset($this->accounts[$number])) {
            $this->logger->log('warning', "Overwriting previously created account $number");
        }

        try {
            $this->accounts[$number] = $this->factory->createAccount($number, $description);
        } catch (Exception\RuntimeException $e) {
            $this->logger->log('warning', "Unable to create account $number ($description): {$e->getMessage()}");
        }
    }

    /**
     * @param string $type Type of account (T, S, I or K)
     */
    public function setAccountType(string $number, string $type): void
    {
        if (!isset(self::ACCOUNT_TYPE_MAP[$type])) {
            $this->logger->log('warning', "Unknown type $type for account number $number");
            return;
        }

        $this->accounts[$number] = new (self::ACCOUNT_TYPE_MAP[$type])(
            $number,
            $this->getAccount($number)->getDescription(),
            $this->getAccount($number)->getAttributes()
        );
    }

    /**
     * @throws Exception\RuntimeException If account does not exist and can not be created
     */
    public function getAccount(string $number): AccountInterface
    {
        if (!isset($this->accounts[$number])) {
            $this->logger->log('warning', "Account number $number not defined", 1);
            $this->addAccount($number, 'UNSPECIFIED');
        }

        if (!isset($this->accounts[$number])) {
            throw new Exception\RuntimeException("Unable to get account $number");
        }

        return $this->accounts[$number];
    }

    /**
     * @return array<AccountInterface>
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }
}
