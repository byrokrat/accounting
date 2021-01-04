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

use byrokrat\accounting\Exception\LogicException;

/**
 * Transaction template data value object
 */
final class TransactionTemplate implements TemplateInterface
{
    /**
     * @param array<string> $dimensions
     * @param array<AttributeTemplate> $attributes
     */
    public function __construct(
        public string $transactionDate = '',
        public string $description = '',
        public string $signature = '',
        public string $amount = '0',
        public string $quantity = '0',
        public string $account = '',
        public array $dimensions = [],
        public array $attributes = [],
    ) {
        foreach ($this->dimensions as $dimension) {
            if (!is_string($dimension)) {
                throw new LogicException('Non-string dimension value found');
            }
        }

        foreach ($this->attributes as $attribute) {
            if (!$attribute instanceof AttributeTemplate) {
                throw new LogicException('Attribute must be instance of AttributeTemplate');
            }
        }
    }

    public function translate(TranslatorInterface $translator): self
    {
        return new self(
            transactionDate: $translator->translate($this->transactionDate),
            description: $translator->translate($this->description),
            signature: $translator->translate($this->signature),
            amount: $translator->translate($this->amount),
            quantity: $translator->translate($this->quantity),
            account: $translator->translate($this->account),
            dimensions: array_map(
                fn($dimension) => $translator->translate($dimension),
                $this->dimensions
            ),
            attributes: array_map(
                fn($attribute) => $attribute->translate($translator),
                $this->attributes
            ),
        );
    }
}
