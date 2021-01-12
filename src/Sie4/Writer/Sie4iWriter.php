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

namespace byrokrat\accounting\Sie4\Writer;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\AccountInterface;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies\ISOCurrencies;
use Money\MoneyFormatter;

/**
 * SIE 4I file format implementation.
 *
 * NOTE that this is not a complete implementation of the SIE file
 * format. Only subsection 4I is supported (transactions to be
 * imported into a regular accounting software). The porpuse is to
 * enable web applications to export transactions to accounting.
 *
 * This implementation is based on specification 4B from the
 * maintainer (SIE gruppen) dated 2008-09-30.
 *
 * NOTE that writing dimensions other then accounts is not supported.
 */
final class Sie4iWriter
{
    private MoneyFormatter $moneyFormatter;

    private const ACCOUNT_TYPE_MAP = [
        AccountInterface::TYPE_ASSET => 'T',
        AccountInterface::TYPE_COST => 'K',
        AccountInterface::TYPE_DEBT => 'S',
        AccountInterface::TYPE_EARNING => 'I',
    ];

    public function __construct()
    {
        $this->moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());
    }

    public function generateSie(Container $container, MetaData $metaData = null): string
    {
        $metaData = $metaData ?: new MetaData();

        $output = new Output();

        $output->writeln('#FLAGGA 0');
        $output->writeln('#SIETYP 4');
        $output->writeln('#FORMAT PC8');

        // Write meta data

        $output->writeln('#PROGRAM %s %s', $metaData->generatingProgram, $metaData->generatingProgramVersion);

        $output->writeln('#GEN %s %s', $metaData->generationDate->formatSie4(), $metaData->generatingUser);

        $output->writeln('#FNAMN %s', $metaData->companyName);

        if ($metaData->companyIdCode) {
            $output->writeln('#FNR %s', $metaData->companyIdCode);
        }

        if ($metaData->companyOrgNr) {
            $output->writeln('#ORGNR %s', $metaData->companyOrgNr);
        }

        if ($metaData->description) {
            $output->writeln('#PROSA %s', $metaData->description);
        }

        if ($metaData->currency) {
            $output->writeln('#VALUTA %s', $metaData->currency);
        }

        if ($metaData->accountPlanType) {
            $output->writeln('#KPTYP %s', $metaData->accountPlanType);
        }

        // Write accounts

        $container->select()->accounts()->each(function ($account) use ($output) {
            $output->writeln(
                '#KONTO %s %s',
                $account->getId(),
                $account->getDescription()
            );

            $output->writeln(
                '#KTYP %s %s',
                $account->getId(),
                self::ACCOUNT_TYPE_MAP[$account->getType()] ?? ''
            );
        });

        // Write verifications

        $container->select()->verifications()->orderById()->each(function ($ver) use ($output) {
            if (empty($ver->getTransactions())) {
                return;
            }

            $output->writeln(
                '#VER %s %s %s %s %s %s',
                '',
                $ver->getId(),
                $ver->getTransactionDate()->formatSie4(),
                $ver->getDescription(),
                $ver->getRegistrationDate()->formatSie4(),
                $ver->getSignature()
            );

            $output->writeln('{');

            foreach ($ver->getTransactions() as $transaction) {
                if ($transaction->isDeleted()) {
                    continue;
                }

                $output->writeln(
                    "#TRANS %s {} %s %s %s %s %s",
                    $transaction->getAccount()->getId(),
                    $this->moneyFormatter->format($transaction->getAmount()),
                    $transaction->getTransactionDate()->formatSie4(),
                    $transaction->getDescription(),
                    '',
                    $transaction->getSignature()
                );
            }

            $output->writeln('}');
        });

        return $output->getContent();
    }
}
