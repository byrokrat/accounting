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

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Exception;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\accounting\Query;

/**
 * SIE 4I file format implementation.
 *
 * WARNING: This is not a complete implementation of the SIE file
 * format. Only subsection 4I is supported (transactions to be
 * imported into a regular accounting software). The porpuse is to
 * enable web applications to export transactions to accounting.
 *
 * This implementation is based on specification 4B from the
 * maintainer (SIE gruppen) dated 2008-09-30.
 *
 * TODO rewrite using tha same object structure as in Parser
 */
class Writer
{
    /*
    TODO Kontroll att transaktion hör till rätt år görs inte längre...
    if (
        $verification->getTransactionDate() < $settings->getAccountingYearFirstDay()
        || $verification->getTransactionDate() > $settings->getAccountingYearLastDay()
    ) {
        throw new Exception\RuntimeException("Verification date is outside of accounting year");
    }
     */

    public function generate(Settings $settings, Query $verifications): string
    {
        $output = new Output;
        $this->writeHeader($settings, $output);
        $this->writeVerifications($verifications, $output);
        return $output->getContent();
    }

    /**
     * Write file header to output
     */
    public function writeHeader(Settings $settings, Output $output)
    {
        $output->writeln('#FLAGGA 0');
        $output->writeln('#SIETYP 4');
        $output->writeln('#FORMAT PC8');

        $output->writeln(
            '#PROGRAM %s %s',
            $settings->getProgram(),
            $settings->getProgramVersion()
        );

        $output->writeln(
            '#GEN %s %s',
            date('Ymd'),
            $settings->getCreator()
        );

        $output->writeln(
            '#FNAMN %s',
            $settings->getTargetCompany()
        );

        $output->writeln(
            '#PROSA %s',
            $settings->getDescription()
        );

        $output->writeln(
            '#RAR 0 %s %s',
            $settings->getAccountingYearFirstDay()->format('Ymd'),
            $settings->getAccountingYearLastDay()->format('Ymd')
        );
    }

    /**
     * Write account to output
     */
    public function writeAccount(AccountInterface $account, Output $output)
    {
        $output->writeln(
            '#KONTO %s %s',
            $account->getId(),
            $account->getDescription()
        );
        $output->writeln(
            '#KTYP %s %s',
            $account->getId(),
            $this->translateAccountType($account)
        );
    }

    /**
     * Write transaction to output
     */
    public function writeTransaction(TransactionInterface $transaction, Output $output)
    {
        $output->writeln(
            "\t#TRANS %s {} %s",
            $transaction->getAccount()->getId(),
            (string)$transaction->getAmount()
        );
    }

    /**
     * Write verification to output
     */
    public function writeVerification(VerificationInterface $verification, Output $output)
    {
        // TODO validate that verification is balanced...
        $output->writeln(
            '#VER "" "" %s %s',
            $verification->getDescription(),
            $verification->getTransactionDate()->format('Ymd')
        );
        $output->writeln('{');
        foreach ($verification->getTransactions() as $transaction) {
            $this->writeTransaction($transaction, $output);
        }
        $output->writeln('}');
    }

    /**
     * Write verifications to output
     */
    public function writeVerifications(Query $verifications, Output $output)
    {
        // TODO not valid since accounts must be written before verifications...
        $verifications->accounts()->each(function ($account) use ($output) {
            $this->writeAccount($account, $output);
        });

        $verifications->verifications()->each(function ($verification) use ($output) {
            $this->writeVerification($verification, $output);
        });
    }

    /**
     * Translate account into one character account type identifier
     */
    private function translateAccountType(AccountInterface $account): string
    {
        // TODO Bryt loss allt översättande från class till typ osv...
        if ($account->isAsset()) {
            return 'T';
        }
        if ($account->isCost()) {
            return 'K';
        }
        if ($account->isDebt()) {
            return 'S';
        }
        if ($account->isEarning()) {
            return 'I';
        }
        // TODO error if this line is reached
    }
}
