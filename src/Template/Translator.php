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
 * Copyright 2016-18 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Template;

/**
 * Translate raw data by expanding placeholders
 */
class Translator
{
    /**
     * @var array
     */
    private $translations;

    public function __construct(array $translations)
    {
        $this->translations = array_combine(
            array_map(
                function (string $key): string {
                    return '{' . $key . '}';
                },
                array_keys($translations)
            ),
            $translations
        );
    }

    public function translate(array $rawArray): array
    {
        return self::arrayMapRecursive(
            function (string $rawValue): string {
                return trim(
                    str_replace(
                        array_keys($this->translations),
                        $this->translations,
                        $rawValue
                    )
                );
            },
            $rawArray
        );
    }

    private static function arrayMapRecursive(callable $callback, array $original): array
    {
        $mapped = [];

        foreach ($original as $key => $value) {
            $mapped[$key] = is_array($value) ? self::arrayMapRecursive($callback, $value) : $callback($value);
        }

        return $mapped;
    }
}
