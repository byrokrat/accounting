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

/**
 * SIE 4I file format implementation.
 *
 * NOTE: This is not a complete implementation of the SIE file
 * format. Only subsection 4I is supported (transactions to be
 * imported into a regular accounting software). The porpuse is to
 * enable web applications to export transactions to accounting.
 *
 * This implementation is based on specification 4B from the
 * maintainer (SIE gruppen) dated 2008-09-30.
 */
final class Sie4Writer
{
    /**
     * Account type to SIE identifier map
     */
    private const ACCOUNT_TYPE_MAP = [
        AccountInterface::TYPE_ASSET => 'T',
        AccountInterface::TYPE_COST => 'K',
        AccountInterface::TYPE_DEBT => 'S',
        AccountInterface::TYPE_EARNING => 'I',
    ];

    /**
     * Generate SIE content from container data
     *
     * NOTE that writing dimensions other then accounts is currently noy supported.
     *
     * NOTE that it is the responsibility of the user to validate the generated
     * contents. Notably verifications must contain transactions and be
     * balanced, and any written attribute contents must be formatted correctly.
     */
    public function generateSie(Container $container, \DateTimeInterface $generationDate = null): string
    {
        $output = new Output();

        $output->writeln('#FLAGGA 0');
        $output->writeln('#SIETYP 4');
        $output->writeln('#FORMAT PC8');

        $output->writeln(
            '#PROGRAM %s %s',
            $container->getAttribute('generating_program', 'byrokrat/accounting'),
            $container->getAttribute('generating_program_version')
        );

        $output->writeln(
            '#GEN %s %s',
            ($generationDate ?: new \DateTime())->format('Ymd'),
            $container->getAttribute('generating_user')
        );

        $output->writeln('#FNAMN %s', $container->getAttribute('company_name'));

        if ($container->hasAttribute('company_type')) {
            $output->writeln('#FTYP %s', $container->getAttribute('company_type'));
        }

        if ($container->hasAttribute('company_id')) {
            $output->writeln('#FNR %s', $container->getAttribute('company_id'));
        }

        if ($container->hasAttribute('company_org_nr')) {
            $output->writeln('#ORGNR %s', $container->getAttribute('company_org_nr'));
        }

        if ($container->hasAttribute('company_address')) {
            $output->writeln(
                '#ADRESS %s %s %s %s',
                $container->getAttribute('company_address', [])['contact'] ?? '',
                $container->getAttribute('company_address', [])['street'] ?? '',
                $container->getAttribute('company_address', [])['postal'] ?? '',
                $container->getAttribute('company_address', [])['phone'] ?? ''
            );
        }

        if ($container->hasAttribute('description')) {
            $output->writeln('#PROSA %s', $container->getAttribute('description'));
        }

        $output->writeln('#VALUTA %s', $container->getAttribute('currency', 'SEK'));

        if ($container->hasAttribute('taxation_year')) {
            $output->writeln('#TAXAR %s', $container->getAttribute('taxation_year'));
        }

        if ($container->hasAttribute('account_plan_type')) {
            $output->writeln('#KPTYP %s', $container->getAttribute('account_plan_type'));
        }

        foreach (range(-5, 5) as $year) {
            if ($container->hasAttribute("financial_year[$year]")) {
                $output->writeln(
                    '#RAR %s %s %s',
                    (string)$year,
                    $container->getAttribute("financial_year[$year]", [])[0] ?? '',
                    $container->getAttribute("financial_year[$year]", [])[1] ?? ''
                );
            }
        }

        // Write accounts

        $container->select()->uniqueAccounts()->each(function ($account) use ($output) {
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

            if ($account->hasAttribute('unit')) {
                $output->writeln(
                    '#ENHET %s %s',
                    $account->getId(),
                    $account->getAttribute('unit')
                );
            }
        });

        // Write verifications

        $comp = function ($left, $right) {
            return $left->getVerificationId() <=> $right->getVerificationId();
        };

        $container->select()->verifications()->orderBy($comp)->each(function ($ver) use ($output) {
            $output->writeln(
                '#VER %s %s %s %s %s %s',
                $ver->getAttribute('series', ''),
                (string)$ver->getVerificationId() ?: '',
                $ver->getTransactionDate()->format('Ymd'),
                $ver->getDescription(),
                $ver->getRegistrationDate()->format('Ymd'),
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
                    $transaction->getAmount()->getString(2),
                    $transaction->getTransactionDate()->format('Ymd'),
                    $transaction->getDescription(),
                    $transaction->getQuantity()->getString(2),
                    $transaction->getSignature()
                );
            }

            $output->writeln('}');
        });

        return $output->getContent();
    }
}
