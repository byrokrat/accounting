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

namespace byrokrat\accounting;

/**
 * Calculate transaction summaries for registered dimensions
 */
class Processor
{
    public function processContainer(Container $container)
    {
        $container->select()->filterType(Dimension::CLASS)->whereUnique()->each(function ($dim) {
            $dim->setAttribute('transactions', []);

            $dim->setAttribute(
                'summary',
                new Summary($dim->hasAttribute('incoming_balance') ? $dim->getAttribute('incoming_balance') : null)
            );

            $dim->setAttribute(
                'quantity_summary',
                new Summary($dim->hasAttribute('incoming_quantity') ? $dim->getAttribute('incoming_quantity') : null)
            );
        });

        $updateDim = function ($dim, $transaction) {
            $dim->getAttribute('transactions')[] = $transaction;
            $dim->getAttribute('summary')->addAmount($transaction->getAmount());
            $dim->getAttribute('quantity_summary')->addAmount($transaction->getQuantity());
        };

        $container->select()->transactions()->each(function ($transaction) use ($updateDim) {
            $updateDim($transaction->getAccount(), $transaction);

            foreach ($transaction->getDimensions() as $dim) {
                $updateDim($dim, $transaction);
            }
        });
    }
}
