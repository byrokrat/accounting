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
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Queryable;
use byrokrat\accounting\Interfaces\Traits\AttributableTrait;
use byrokrat\accounting\Interfaces\Traits\DescribableTrait;
use byrokrat\amount\Amount;

/**
 * Build verifications from preconstructed templates
 */
class Template implements Attributable, Describable
{
    use AttributableTrait, DescribableTrait;

    /**
     * @var string Template identifier
     */
    private $templateId;

    /**
     * @var array Raw template transactions
     */
    private $transactions = [];

    /**
     * Set template values
     */
    public function __construct(string $templateId, string $description, array ...$transactions)
    {
        $this->templateId = $templateId;
        $this->setDescription($description);
        foreach ($transactions as $transactionData) {
            $this->addTransaction(...$transactionData);
        }
    }

    /**
     * Get template identifier
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    /**
     * Add transaction data
     *
     * Substitution variables with the form {var} can be used
     */
    public function addTransaction(string $number, string $amount, string $quantity = '', array $dimensions = []): self
    {
        $this->transactions[] = [$number, $amount, $quantity, $dimensions];

        return $this;
    }

    /**
     * Create verification from template data
     *
     * @param string[]  $translationMap Substitution key-value-pairs
     * @param Queryable $container      Queryable object containing account data
     *
     * @throws Exception\RuntimeException If any key is NOT translated
     */
    public function build(array $translationMap, Queryable $container): Verification
    {
        $container = $container->query();
        $filter = $this->createTranslationFilter($translationMap);

        $ver = (new Verification)->setDescription(
            $filter($this->getDescription())
        );

        foreach ($this->transactions as list($number, $amount, $quantity, $dimensions)) {
            $dimensions = array_map(
                function ($number) use ($container, $filter) {
                    return $container->findDimensionFromNumber(intval($filter($number)));
                },
                $dimensions
            );

            $ver->addTransaction(
                new Transaction(
                    $container->findAccountFromNumber(intval($filter($number))),
                    new Amount($filter($amount)),
                    intval($filter($quantity)),
                    ...$dimensions
                )
            );
        }

        foreach ($this->getAttributes() as $name => $value) {
            $ver->setAttribute(
                $filter($name),
                $filter($value)
            );
        }

        return $ver;
    }

    /**
     * Create translation callable for values in $map
     */
    private function createTranslationFilter(array $map): callable
    {
        return new class($map) {
            private $map;

            public function __construct(array $map)
            {
                $this->map = array_combine(
                    array_map(
                        function ($key) {
                            return '{' . $key . '}';
                        },
                        array_keys($map)
                    ),
                    $map
                );
            }

            public function __invoke(string $value): string
            {
                return $this->validate(
                    $this->translate($value)
                );
            }

            private function translate(string $value): string
            {
                return trim(
                    str_replace(
                        array_keys($this->map),
                        $this->map,
                        $value
                    )
                );
            }

            private function validate(string $value): string
            {
                if (preg_match("/\{[^}]*\}/", $value)) {
                    throw new Exception\RuntimeException("Unable to substitute template key: $value");
                }

                return $value;
            }
        };
    }
}
