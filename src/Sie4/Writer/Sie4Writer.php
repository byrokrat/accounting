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
class Sie4Writer
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
     * TODO beskriv vilka attribute som används..
     *
     * NOTE that writing dimensions other then accounts is currently noy supported.
     *
     * NOTE that it is the responsibility of the user to validate the generated
     * contents. Notably verifications must contain transactions and be
     * balanced, and any written attribute contents must be formatted correctly.
     */
    public function generateSie(Container $container): string
    {
        $output = new Output;

        $output->writeln('#FLAGGA 0');
        $output->writeln('#SIETYP 4');
        $output->writeln('#FORMAT PC8');

        // TODO kolla vad attributes heter i parser...

        // TODO valuta måste skrivas ut..., använd SEK som default....

        // TODO vilka fler är mandatory???

        if ($container->hasAttribute('generating_program')) {
            $output->writeln(
                '#PROGRAM %s %s',
                $container->getAttribute('generating_program'),
                $container->getAttribute('generating_program_version')
            );
        }

        // TODO vill vi att datum ska vara med på bättre sätt??
            // förstå att det kan vara \DateTimeInterface ??
            // default till dagens datum??
            // skicka med $now vid method call??????

        if ($container->hasAttribute('generation_date')) {
            $output->writeln(
                '#GEN %s %s',
                $container->getAttribute('generation_date'),
                $container->getAttribute('generating_user')
            );
        }

        if ($container->hasAttribute('company_name')) {
            $output->writeln(
                '#FNAMN %s',
                $container->getAttribute('company_name')
            );
        }

        if ($container->hasAttribute('description')) {
            $output->writeln(
                '#PROSA %s',
                $container->getAttribute('description')
            );
        }

        if ($container->hasAttribute('accounting_year_start')) {
            $output->writeln(
                '#RAR 0 %s %s',
                $container->getAttribute('accounting_year_start'),
                $container->getAttribute('accounting_year_end')
            );
        }

        // Write accounts

        $writtenAccounts = [];

        $container->select()->accounts()->each(function ($account) use ($output, &$writtenAccounts) {
            if (isset($writtenAccounts[$account->getId()])) {
                return;
            }

            $writtenAccounts[$account->getId()] = true;

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

        $comp = function ($left, $right) {
            return $left->getVerificationId() <=> $right->getVerificationId();
        };

        $writtenVers = [];

        $container->select()->verifications()->orderBy($comp)->each(function ($ver) use ($output, &$writtenVers) {
            if (isset($writtenVers[$ver->getVerificationId()])) {
                return;
            }

            $writtenVers[$ver->getVerificationId()] = true;

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
