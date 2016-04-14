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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\accounting\Sie;

use byrokrat\accounting\Verification;
use byrokrat\accounting\Account;
use byrokrat\accounting\AccountSet;
use byrokrat\accounting\Exception;

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
    /**
     * @var Settings Loaded SIE settings
     */
    private $settings;

    /**
     * @var Verification[] Loaded verifications
     */
    private $verifications = [];

    /**
     * Load settings at construct
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Clear added verifications
     */
    public function reset()
    {
        $this->verifications = [];
    }

    /**
     * Add one or more verifications
     *
     * @throws Exception\UnexpectedValueException If verification is unbalanced
     * @throws Exception\OutOfBoundsException     If date is outside of accounting year
     */
    public function addVerification(Verification ...$verifications)
    {
        foreach ($verifications as $verification) {
            if (!$verification->isBalanced()) {
                throw new Exception\UnexpectedValueException("Verification is not balanced");
            }

            list($firstDay, $lastDay) = $this->settings->getAccountingYear();
            if ($verification->getDate() < $firstDay || $verification->getDate() > $lastDay) {
                throw new Exception\OutOfBoundsException("Verification date is outside of accounting year");
            }

            $this->verifications[] = $verification;
        }
    }

    /**
     * Get loaded verifications
     *
     * @return Verification[]
     */
    public function getVerifications(): array
    {
        return $this->verifications;
    }

    /**
     * Get accounts used in loaded verifications
     *
     * @return Account[]
     */
    public function getAccounts(): array
    {
        $accounts = [];
        foreach ($this->verifications as $verification) {
            foreach ($verification->getAccounts() as $account) {
                $accounts[$account->getNumber()] = $account;
            }
        }

        return $accounts;
    }


    /**
     * Remove control characters, addslashes and quote $str
     */
    public static function quote(string $str): string
    {
        $str = preg_replace('/[[:cntrl:]]/', '', $str);
        $str = addslashes($str);
        return "\"$str\"";
    }

    /**
     * Generate SIE string (using charset CP437)
     *
     * @return string
     */
    public function generate()
    {
        // TODO verifications och settings kan komma hit allesammans!!
            // Med hjälp av Sie\VerificationBag eller liknande...
            // Eller ska vi ha en accounting\VerificationSet, kan ju vara bra i andra tillfällen mä...
        $stream = new Stream;

        $stream->writeln('#FLAGGA 0');

        // TODO %s ska ersättas med quoted values!!!
        $stream->writeln(
            '#PROGRAM %s %s',
            $this->settings->getProgram(),
            $this->settings->getProgramVersion()
        );

        $strem->writeln('#FORMAT PC8');

        $strem->writeln(
            '#GEN '.date('Ymd').' %s',
            $this->settings->getCreator(),
        );

        $strem->writeln('#SIETYP 4');

        $strem->writeln(
            '#FNAMN %s',
            $this->settings->getCompany(),
        );

        $strem->writeln(
            '#KPTYP %s',
            $this->settings->getChartType(),
        );

        list($firstDay, $lastDay) = $this->settings->getAccountingYear();
        $strem->writeln(
            "#RAR 0 {$firstDay->format('Ymd')} {$lastDay->format('Ymd')}"
        );

        $stream->writeln('');

        foreach ($this->getAccounts() as $account) {
            $stream->writeln(
                '#KONTO %s %s',
                (string)$account->getNumber(),
                $account->getName()
            );

            // TODO translatedAccountType() ska implementeras på annat sätt...
            $stream->writeln(
                '#KTYP %s %s',
                (string)$account->getNumber(),
                $this->translateAccountType($account)
            );
        }

        foreach ($this->getVerifications() as $verification) {
            // TODO verifications har jag inte gjort, se nedan...
        }

        // Generate header
        //$program = self::quote($this->program);
        //$version = self::quote($this->version);
        //$creator = self::quote($this->creator);
        //$company = self::quote($this->company);
        //$chartType = self::quote($this->typeOfChart);

        $sie = '';
        //$sie = "#FLAGGA 0" . self::EOL;
        //$sie .= "#PROGRAM $program $version" . self::EOL;
        //$sie .= "#FORMAT PC8" . self::EOL;
        //$sie .= "#GEN {$this->date->format('Ymd')} $creator" . self::EOL;
        //$sie .= "#SIETYP 4" . self::EOL;
        //$sie .= "#FNAMN $company" . self::EOL;
        //$sie .= "#KPTYP $chartType" . self::EOL;

        /*if (isset($this->yearStart)) {
            $start = $this->yearStart->format('Ymd');
            $stop = $this->yearStop->format('Ymd');
            $sie .= "#RAR 0 $start $stop" . self::EOL;
        }*/

        //$sie .= self::EOL;

        // Generate accounts
        /*foreach ($this->usedAccounts as $account) {
            $number = self::quote((string)$account->getNumber());
            $name = self::quote($account->getName());
            $type = self::quote($this->translateAccountType($account));
            $sie .= "#KONTO $number $name" . self::EOL;
            $sie .= "#KTYP $number $type" . self::EOL;
        }*/

        // Generate verifications
        foreach ($this->verifications as $ver) {
            $text = self::quote($ver->getText());
            $date = $ver->getDate()->format('Ymd');
            $sie .= self::EOL . "#VER \"\" \"\" $date $text" . self::EOL;
            $sie .= "{" . self::EOL;
            foreach ($ver->getTransactions() as $trans) {
                $sie .=
                    "\t#TRANS {$trans->getAccount()->getNumber()} {} "
                    . $trans->getAmount()
                    . self::EOL;
            }
            $sie .= "}" . self::EOL;
        }

        // Convert charset
        //$sie = iconv("UTF-8", "CP437", $sie);

        return $stream->getContent();
    }

    /**
     * Generate SIE string (using charset CP437) for $accounts
     *
     * @param  string     $description String describing this chart of accounts
     * @param  AccountSet $accounts
     */
    public function exportChart(string $description, AccountSet $accounts): string
    {
        // Generate header
        $program = self::quote($this->program);
        $description = self::quote($description);
        $version = self::quote($this->version);
        $creator = self::quote($this->creator);
        $chartType = 'TODO'; //self::quote($chart->getChartType());

        $sie = "#FILTYP KONTO" . self::EOL;
        $sie .= "#PROGRAM $program $version" . self::EOL;
        $sie .= "#TEXT $description" . self::EOL;
        $sie .= "#FORMAT PC8" . self::EOL;
        $sie .= "#GEN {$this->date->format('Ymd')} $creator" . self::EOL;
        $sie .= "#KPTYP $chartType" . self::EOL;

        $sie .= self::EOL;

        // Generate accounts
        foreach ($accounts->getAccounts() as $account) {
            $number = self::quote((string)$account->getNumber());
            $name = self::quote($account->getName());
            $type = self::quote($this->translateAccountType($account));
            $sie .= "#KONTO $number $name" . self::EOL;
            $sie .= "#KTYP $number $type" . self::EOL;
        }

        // Convert charset
        $sie = iconv("UTF-8", "CP437", $sie);

        return $sie;
    }

    /**
     * Translate account into one character account type identifier
     */
    private function translateAccountType(Account $account): string
    {
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
     * Create an AccountSet object from SIE string (in charset CP437)
     *
     * @throws Exception\RangeException If $sie is not valid
     */
    public function importChart(string $sie): AccountSet
    {
        $sie = iconv("CP437", "UTF-8", $sie);
        $lines = explode(self::EOL, $sie);

        $accounts = new AccountSet();
        $current = array();

        foreach ($lines as $nr => $line) {
            $data = str_getcsv($line, ' ', '"');
            switch ($data[0]) {
                case '#KPTYP':
                    if (!isset($data[1])) {
                        throw new Exception\RangeException("Invalid chart type at line $nr");
                    }
                    // TODO not supported at the moment
                    // $accounts->setChartType($data[1]);
                    break;
                case '#KONTO':
                    // Account must have form #KONTO number name
                    if (!isset($data[2])) {
                        throw new Exception\RangeException("Invalid account values at line $nr");
                    }
                    $current = array($data[1], $data[2]);
                    break;
                case '#KTYP':
                    // Account type must have form #KTYP number type
                    if (!isset($data[2])) {
                        throw new Exception\RangeException("Invalid account values at line $nr");
                    }
                    // Type must referer to current account
                    if ($data[1] != $current[0]) {
                        throw new Exception\RangeException("Unexpected account type at line $nr");
                    }

                    switch ($data[2]) {
                        case 'T':
                            $account = new Account\Asset(intval($data[1]), $current[1]);
                            break;
                        case 'I':
                            $account = new Account\Earning(intval($data[1]), $current[1]);
                            break;
                        case 'S':
                            $account = new Account\Debt(intval($data[1]), $current[1]);
                            break;
                        case 'K':
                            $account = new Account\Cost(intval($data[1]), $current[1]);
                            break;
                    }

                    $accounts->addAccount($account);
                    $current = array();
                    break;
            }
        }

        // There should be no half way processed accounts
        if (!empty($current)) {
            throw new Exception\RangeException("Account type missing for '{$current[0]}'");
        }

        return $accounts;
    }
}
