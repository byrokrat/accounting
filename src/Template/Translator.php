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
 * Translate raw data by expanding placeholders
 */
final class Translator implements TranslatorInterface
{
    /** @var array<string, string> */
    private array $translations;

    /**
     * @param array<string, string> $translations
     */
    public function __construct(array $translations)
    {
        foreach ($translations as $placeholder => $replacement) {
            if (!is_string($placeholder)) {
                throw new LogicException('TypeError: Placeholder must be string');
            }

            if (!is_string($replacement)) {
                throw new LogicException('TypeError: Replacement must be string');
            }
        }

        $this->translations = (array)array_combine(
            array_map(
                fn ($placeholder) => '{' . $placeholder . '}',
                array_keys($translations)
            ),
            $translations
        );
    }

    public function translate(string $raw): string
    {
        return str_replace(
            array_keys($this->translations),
            $this->translations,
            $raw
        );
    }
}
