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
use byrokrat\accounting\AccountFactory;
use byrokrat\accounting\Exception;
use Psr\Log\LoggerInterface;

/**
 * Builder that creates and keeps track of account objects
 */
class AccountBuilder
{
    /**
     * @var string[] Map of account type identifier to class name
     */
    private static $accountTypeMap = [
        'T' => Account\Asset::CLASS,
        'S' => Account\Debt::CLASS,
        'K' => Account\Cost::CLASS,
        'I' => Account\Earning::CLASS
    ];

    /**
     * @var Account[] Created account objects
     */
    private $accounts = [];

    /**
     * @var AccountFactory
     */
    private $factory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Inject account factory and logger att construct
     */
    public function __construct(AccountFactory $factory, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->logger = $logger;
    }

    /**
     * Create a new account object
     *
     * @param  string $number      Number of account
     * @param  string $description Free text description of account
     * @return void
     */
    public function addAccount(string $number, string $description)
    {
        if (isset($this->accounts[$number])) {
            $this->logger->warning("Overwriting previously created account $number");
        }

        try {
            $this->accounts[$number] = $this->factory->createAccount($number, $description);
        } catch (Exception\RuntimeException $e) {
            $this->logger->warning("Unable to create account $number ($description): {$e->getMessage()}");
        }
    }

    /**
     * Set a new type of account
     *
     * @param  string $number  Number of account
     * @param  string $type    Type of account (T, S, I or K)
     * @return void
     */
    public function setAccountType(string $number, string $type)
    {
        if (!isset(self::$accountTypeMap[$type])) {
            $this->logger->warning("Unknown type $type for account number $number");
            return;
        }

        $this->accounts[$number] = new self::$accountTypeMap[$type](
            $number,
            $this->getAccount($number)->getDescription(),
            $this->getAccount($number)->getAttributes()
        );
    }

    /**
     * Get account from internal store using account number as key
     *
     * @throws Exception\RuntimeException If account does not exist and can not be created
     */
    public function getAccount(string $number): Account
    {
        if (!isset($this->accounts[$number])) {
            $this->logger->warning("Account number $number not defined", ['_addToLineCount' => 1]);
            $this->addAccount($number, 'UNSPECIFIED');
        }

        if (!isset($this->accounts[$number])) {
            throw new Exception\RuntimeException("Unable to get account $number");
        }

        return $this->accounts[$number];
    }

    /**
     * Get creaated accounts
     *
     * @return Account[]
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }
}
