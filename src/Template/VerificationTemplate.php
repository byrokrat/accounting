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
 * Copyright 2016-20 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Exception\LogicException;

/**
 * Verification template data value object
 *
 * @TODO use named arguments and constructor arg promotion instead of arrays
 * @TODO break out transactions ot TransactionTemplate
 */
class VerificationTemplate
{
    private const VERIFICATION_DEFAULT_VALUES = [
        'id' => '0',
        'transaction_date' => '{now}',
        'registration_date' => '{now}',
        'description' => '',
        'signature' => '',
        'transactions' => [],
        'attributes' => []
    ];

    private const TRANSACTION_DEFAULT_VALUES = [
        'transaction_date' => '{verification_transaction_date}',
        'description' => '{verification_description}',
        'signature' => '{verification_signature}',
        'amount' => '0',
        'quantity' => '0',
        'account' => '',
        'dimensions' => []
    ];

    /**
     * Names of array indices that must contain arrays
     *
     * @TODO should be unneccesary once named arguments is used
     */
    private const ARRAY_TYPE_KEYS = [
        'transactions',
        'attributes',
        'dimensions'
    ];

    /** @var array<string, string|array> */
    private array $values;

    /**
     * @param array<string, string|array> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_merge(self::VERIFICATION_DEFAULT_VALUES, $values);

        self::validateTypes($this->values);

        if (!is_array($this->values['transactions'])) {
            throw new LogicException("transactios data must be array");
        }

        foreach ($this->values['transactions'] as &$transData) {
            if (!is_array($transData)) {
                throw new LogicException("Transaction data must be array");
            }

            $transData = array_merge(self::TRANSACTION_DEFAULT_VALUES, $transData);

            self::validateTypes($transData);
        }
    }

    /**
     * @return array<string, string|array>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array<string, string|array> $values
     */
    private static function validateTypes(array $values): void
    {
        foreach (new \RecursiveArrayIterator($values) as $key => $value) {
            if (in_array($key, self::ARRAY_TYPE_KEYS)) {
                if (gettype($value) != 'array') {
                    throw new LogicException("Template indice $key must contain array");
                }
                continue;
            }

            if (gettype($value) != 'string') {
                throw new LogicException("Template indice $key must contain string");
            }
        }
    }
}
