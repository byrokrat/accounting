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

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\accounting\Verification\Verification;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

final class TemplateRenderer
{
    public function __construct(
        private Query $dimensionQuery,
        private MoneyFactoryInterface $moneyFactory,
        private DateFactory $dateFactory,
    ) {}

    public function render(VerificationTemplate $template, TranslatorInterface $translator): VerificationInterface
    {
        $template = $template->translate($translator);

        if (!ctype_digit($template->id)) {
            throw new RuntimeException('Verification id must be a string of integers');
        }

        return new Verification(
            id: (int)$template->id,
            transactionDate: $this->dateFactory->createDate($template->transactionDate),
            registrationDate: $this->dateFactory->createDate($template->registrationDate),
            description: $template->description,
            signature: $template->signature,
            transactions: array_map(
                fn($transTmpl) => $this->renderTransaction($transTmpl, $template),
                $template->transactions
            ),
            attributes: array_combine(
                array_map(fn($attr) => $attr->key, $template->attributes),
                array_map(fn($attr) => $attr->value, $template->attributes),
            ),
        );
    }

    /**
     * @TODO Quantity should be validated as a float-style number string. Here or in a factory.
     * Or wrapp creation on a try block so we throw an internal exception..
     */
    private function renderTransaction(TransactionTemplate $transTmpl, VerificationTemplate $verTmpl): Transaction
    {
        return new Transaction(
            verificationId: (int)$verTmpl->id,
            transactionDate: $this->dateFactory->createDate($transTmpl->transactionDate ?: $verTmpl->transactionDate),
            description: $transTmpl->description ?: $verTmpl->description,
            signature: $transTmpl->signature ?: $verTmpl->signature,
            amount: $this->moneyFactory->createMoney($transTmpl->amount),
            quantity: new Amount($transTmpl->quantity),
            account: $this->dimensionQuery->getAccount($transTmpl->account),
            dimensions: array_map(
                fn($dimId) => $this->dimensionQuery->getDimension($dimId),
                $transTmpl->dimensions
            ),
            attributes: array_combine(
                array_map(fn($attr) => $attr->key, $transTmpl->attributes),
                array_map(fn($attr) => $attr->value, $transTmpl->attributes),
            ),
        );
    }
}
