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

/**
 * Translate raw data by expanding placeholders
 *
 * @TODO felmeddelanden om ej string|Stringable skickas med??
 */
class Translator
{
    /** @var array<string, string> */
    private array $translations;

    /**
     * @param array<string, string> $translations
     */
    public function __construct(array $translations)
    {
        $this->translations = (array)array_combine(
            array_map(
                fn (string $key) => '{' . $key . '}',
                array_keys($translations)
            ),
            $translations
        );
    }

    /**
     * @param array<string, string|array> $raw
     * @return array<string, string|array>
     */
    public function translate(array $raw): array
    {
        return self::arrayMapRecursive(
            function (string $value): string {
                return trim(
                    str_replace(
                        array_keys($this->translations),
                        $this->translations,
                        $value
                    )
                );
            },
            $raw
        );
    }

    /**
     * @param array<string, string|array> $raw
     * @return array<string, string|array>
     */
    private static function arrayMapRecursive(callable $callback, array $raw): array
    {
        $mapped = [];

        foreach ($raw as $key => $value) {
            $mapped[$key] = is_array($value) ? self::arrayMapRecursive($callback, $value) : $callback($value);
        }

        return $mapped;
    }
}
