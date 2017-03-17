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
 * Copyright 2016-17 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting;

/**
 * Calculate transaction summaries for registered dimensions
 */
class Processor
{
    public function processContainer(Container $container)
    {
        $container->select()->filterType(Dimension::CLASS)->whereUnique()->each(function ($dimension) {
            $dimension->resetSummary();
        });

        $container->select()->transactions()->each(function ($transaction) {
            $transaction->getAccount()->getSummary()->addTransaction($transaction);

            foreach ($transaction->getDimensions() as $dimension) {
                $dimension->getSummary()->addTransaction($transaction);
            }
        });
    }
}
