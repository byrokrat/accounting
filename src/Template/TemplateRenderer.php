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
 * Copyright 2016-19 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Transaction\Transaction;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\accounting\Verification\Verification;
use byrokrat\accounting\QueryableInterface;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

class TemplateRenderer
{
    /**
     * @var Query
     */
    private $dimensionQuery;

    /**
     * @var MoneyFactoryInterface
     */
    private $moneyFactory;

    /**
     * @var DateFactory
     */
    private $dateFactory;

    public function __construct(
        QueryableInterface $container,
        MoneyFactoryInterface $moneyFactory = null,
        DateFactory $dateFactory = null
    ) {
        $this->dimensionQuery = $container->select();
        $this->moneyFactory = $moneyFactory ?: new SekMoneyFactory;
        $this->dateFactory = $dateFactory ?: new DateFactory;
    }

    public function render(VerificationTemplate $template, Translator $translator): VerificationInterface
    {
        $data = $translator->translate($template->getValues());

        $relations = new Translator([
            'verification_transaction_date' => (string)($data['transaction_date'] ?? ''),
            'verification_description' => (string)($data['description'] ?? ''),
            'verification_signature' => (string)($data['signature'] ?? ''),
        ]);

        $data = $relations->translate($data);

        $transactions = [];

        foreach ((array)($data['transactions'] ?? []) as $transData) {
            $dimensions = [];

            foreach ((array)($transData['dimensions'] ?? []) as $rawDim) {
                $dimensions[] = $this->dimensionQuery->getDimension($rawDim);
            }

            $transactions[] = new Transaction(
                (int)($data['id'] ?? ''),
                $this->dateFactory->createDate((string)($transData['transaction_date'] ?? '')),
                (string)($transData['description'] ?? ''),
                (string)($transData['signature'] ?? ''),
                $this->moneyFactory->createMoney((string)($transData['amount'] ?? '')),
                new Amount((string)($transData['quantity'] ?? '')),
                $this->dimensionQuery->getAccount((string)($transData['account'] ?? '')),
                ...$dimensions
            );
        }

        $verification =  new Verification(
            (int)($data['id'] ?? ''),
            $this->dateFactory->createDate((string)($data['transaction_date'] ?? '')),
            $this->dateFactory->createDate((string)($data['registration_date'] ?? '')),
            (string)($data['description'] ?? ''),
            (string)($data['signature'] ?? ''),
            ...$transactions
        );

        foreach ((array)($data['attributes'] ?? '') as $key => $value) {
            $verification->setAttribute((string)$key, $value);
        }

        return $verification;
    }
}
