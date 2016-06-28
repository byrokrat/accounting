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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

/**
 * Build verifications from preconstructed templates
 */
class Template
{
    /**
     * @var string Template identifier
     */
    private $templateId;

    /**
     * @var string Raw verification description
     */
    private $description;

    /**
     * @var array Raw template transactions
     */
    private $transactions = [];

    /**
     * Set template values
     */
    public function __construct(string $templateId, string $description)
    {
        $this->templateId = $templateId;
        $this->description = $description;
    }

    /**
     * Get template identifier
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    /**
     * Get raw verification description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Add transaction data
     *
     * Substitution variables with the form {var} can be used
     */
    public function addRawTransaction(string $number, string $amount)
    {
        $this->transactions[] = [$number, $amount];
    }

    /**
     * Get loaded transaction data
     *
     * @return array
     */
    public function getRawTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Substitute template variables in verification description and transactions
     *
     * @param string[] $values Substitution key-value-pairs
     */
    public function substitute(array $values)
    {
        // Create map of substitution keys
        $keys = array_map(
            function ($val) {
                return '{' . $val . '}';
            },
            array_keys($values)
        );

        // Substitute terms in verification description
        $this->description = trim(str_replace($keys, $values, $this->description));

        // Substitue terms in transactions
        $this->transactions = array_map(
            function ($data) use ($keys, $values) {
                $data[0] = trim(str_replace($keys, $values, $data[0]));
                $data[1] = trim(str_replace($keys, $values, $data[1]));
                return $data;
            },
            $this->transactions
        );
    }

    /**
     * Check if template is ready for conversion (all variables are substituted)
     *
     * @param string $key Will contian non-substituted key on error
     */
    public function ready(&$key): bool
    {
        foreach ($this->getRawTransactions() as $transactionData) {
            foreach ($transactionData as $key) {
                if (preg_match("/\{[^}]*\}/", $key)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Create verification from template
     *
     * @param  Query $data Query object containing account data
     * @throws Exception\UnexpectedValueException If any key is NOT substituted
     */
    public function buildVerification(Query $data): Verification
    {
        if (!$this->ready($key)) {
            throw new Exception\UnexpectedValueException("Unable to substitute template key $key");
        }

        $ver = new Verification($this->getDescription());

        foreach ($this->getRawTransactions() as list($number, $amount)) {
            $ver->addTransaction(
                new Transaction(
                    $data->findAccountFromNumber(intval($number)),
                    new Amount($amount)
                )
            );
        }

        return $ver;
    }
}
