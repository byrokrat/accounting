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

namespace byrokrat\accounting\Sie;

use byrokrat\accounting\Account;
use byrokrat\accounting\Exception;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Verification;
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
 */
class Writer
{
    /*
    TODO Kontroll att transaktion hör till rätt år görs inte längre...
    if (
        $verification->getDate() < $settings->getAccountingYearFirstDay()
        || $verification->getDate() > $settings->getAccountingYearLastDay()
    ) {
        throw new Exception\RuntimeException("Verification date is outside of accounting year");
    }
     */

    public function generate(SettingsInterface $settings, Query $verifications): string
    {
        $output = new Output;
        $this->writeHeader($settings, $output);
        $this->writeVerifications($verifications, $output);
        return $output->getContent();
    }

    /**
     * Write file header to output
     */
    public function writeHeader(SettingsInterface $settings, Output $output)
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
    public function writeAccount(Account $account, Output $output)
    {
        $output->writeln(
            '#KONTO %s %s',
            (string)$account->getNumber(),
            $account->getDescription()
        );
        $output->writeln(
            '#KTYP %s %s',
            (string)$account->getNumber(),
            $this->translateAccountType($account)
        );
    }

    /**
     * Write transaction to output
     */
    public function writeTransaction(Transaction $transaction, Output $output)
    {
        $output->writeln(
            "\t#TRANS %s {} %s",
            (string)$transaction->getAccount()->getNumber(),
            (string)$transaction->getAmount()
        );
    }

    /**
     * Write verification to output
     */
    public function writeVerification(Verification $verification, Output $output)
    {
        $output->writeln(
            '#VER "" "" %s %s',
            $verification->getDescription(),
            $verification->getDate()->format('Ymd')
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
    private function translateAccountType(Account $account): string
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


    /**
     * Generate SIE string (using charset CP437) for $accounts
     *
     * @param  string $description String describing this chart of accounts
     * @param  Query  $accounts
     */
    public function exportChart(string $description, Query $accounts): string
    {
        // TODO ska helt enkelt byggas upp i med det nya systemet..
        // TODO eller tas bort helt?

        /*$program = self::quote($this->program);
        $description = self::quote($description);
        $version = self::quote($this->version);
        $creator = self::quote($this->creator);
        $chartType = self::quote('');

        $sie = "#FILTYP KONTO" . self::EOL;
        $sie .= "#PROGRAM $program $version" . self::EOL;
        $sie .= "#PROSA $description" . self::EOL;
        $sie .= "#FORMAT PC8" . self::EOL;
        $sie .= "#GEN {$this->date->format('Ymd')} $creator" . self::EOL;
        $sie .= "#KPTYP $chartType" . self::EOL;

        $sie .= self::EOL;

        // Generate accounts
        foreach ($accounts as $account) {
            $number = self::quote((string)$account->getNumber());
            $name = self::quote($account->getDescription());
            $type = self::quote($this->translateAccountType($account));
            $sie .= "#KONTO $number $name" . self::EOL;
            $sie .= "#KTYP $number $type" . self::EOL;
        }

        // Convert charset
        $sie = iconv("UTF-8", "CP437", $sie);

        return $sie;
        */
    }
}
