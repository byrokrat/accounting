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

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Container;

final class Sie4Parser extends Grammar
{
    /**
     * Parse SIE content
     *
     * Please note that content should be passed in the PC8 charset (code page 437)
     *
     * @param string $content
     */
    public function parse($content): Container
    {
        $this->resetInternalState();

        $content = (string)preg_replace(
            '/[\xFF]/',
            ' ',
            (string)iconv('CP437', 'UTF-8//IGNORE', $content)
        );

        $this->getLogger()->resetLog($content);

        try {
            parent::parse($content);
        } catch (\Exception $e) {
            $this->getLogger()->incrementLineCount();
            $this->getLogger()->log('error', $e->getMessage());
        }

        $container = new Container(
            ...array_values($this->getAccountBuilder()->getAccounts()),
            ...array_values($this->getDimensionBuilder()->getDimensions()),
            ...$this->getParsedItems()
        );

        foreach ($this->getParsedAttributes() as $key => $value) {
            $container->setAttribute($key, $value);
        }

        return $container;
    }

    /**
     * @return array<string>
     */
    public function getErrorLog(): array
    {
        return $this->getLogger()->getLog();
    }
}
