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

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Query;
use byrokrat\accounting\Exception;

/**
 * Callbacks for parsing expressions found in Grammar
 */
class Parser extends Grammar
{
    /**
     * Parse SIE content
     *
     * Please note that content should be passed in the PC8 charset (code page 437)
     *
     * @param  string $content Raw SIE content to parse (code page 437)
     * @return Container The parsed content (utf 8)
     * @throws Exception\ParserException If parsing fails
     */
    public function parse($content)
    {
        $content = preg_replace(
            '/[\xFF]/',
            ' ',
            iconv('CP437', 'UTF-8//IGNORE', $content)
        );

        $this->resetContainer();
        $this->getLogger()->resetLog($content);

        try {
            parent::parse($content);
        } catch (\Exception $e) {
            $this->getLogger()->incrementLineCount();
            $this->getLogger()->error($e->getMessage());
        }

        $this->getContainer()
            ->addItem(new Query($this->getAccountBuilder()->getAccounts()))
            ->addItem(new Query($this->getDimensionBuilder()->getDimensions()));

        if ($this->getLogger()->getLog()) {
            throw new Exception\ParserException($this->getLogger()->getLog());
        }

        return $this->getContainer();
    }
}
