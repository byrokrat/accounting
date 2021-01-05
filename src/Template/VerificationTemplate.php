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

use byrokrat\accounting\Exception\InvalidArgumentException;

/**
 * Verification template data value object
 */
final class VerificationTemplate implements TemplateInterface
{
    /**
     * @param array<TransactionTemplate> $transactions
     * @param array<AttributeTemplate> $attributes
     */
    public function __construct(
        public string $id = '0',
        public string $transactionDate = '{now}',
        public string $registrationDate = '{now}',
        public string $description = '',
        public string $signature = '',
        public array $transactions = [],
        public array $attributes = []
    ) {
        foreach ($this->transactions as $transaction) {
            if (!$transaction instanceof TransactionTemplate) {
                throw new InvalidArgumentException('Transaction must be instance of TransactionTemplate');
            }
        }

        foreach ($this->attributes as $attribute) {
            if (!$attribute instanceof AttributeTemplate) {
                throw new InvalidArgumentException('Attribute must be instance of AttributeTemplate');
            }
        }
    }

    public function translate(TranslatorInterface $translator): self
    {
        return new self(
            id: $translator->translate($this->id),
            transactionDate: $translator->translate($this->transactionDate),
            registrationDate: $translator->translate($this->registrationDate),
            description: $translator->translate($this->description),
            signature: $translator->translate($this->signature),
            transactions: array_map(
                fn($transaction) => $transaction->translate($translator),
                $this->transactions
            ),
            attributes: array_map(
                fn($attribute) => $attribute->translate($translator),
                $this->attributes
            ),
        );
    }
}
