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

use byrokrat\accounting\Account;
use byrokrat\accounting\AccountFactory;
use byrokrat\accounting\Dimension;
use byrokrat\amount\Currency;

/**
 * Callbacks for parsing expressions found in Grammar
 */
class SieParser extends SieGrammar
{
    /**
     * Parse SIE content
     *
     * @param  string $content Raw SIE content to parse
     * @return Container The parsed content
     */
    public function parse($content)
    {
        $this->getLogger()->resetLog();

        try {
            parent::parse($content);
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }

        $this->getLogger()->validateState();

        return $this->getContainer();
    }

    /**
     * Called when an unknown row is encountered
     *
     * @param  string   $label Row label
     * @param  string[] $vars  Row variables
     * @return void
     */
    public function onUnknown(string $label, array $vars)
    {
        // TODO ska flyttas någon annan stans...
        $this->registerWarning(
            array_reduce(
                $vars,
                function ($carry, $var) {
                    return "$carry \"$var\"";
                },
                "Encountered unknown statement: #$label"
            )
        );
    }
}
