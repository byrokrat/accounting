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

namespace byrokrat\accounting\Formatter;

use byrokrat\accounting\Verification;
use byrokrat\accounting\Account;
use byrokrat\accounting\AccountSet;
use byrokrat\accounting\Exception\UnexpectedValueException;
use byrokrat\accounting\Exception\OutOfBoundsException;
use byrokrat\accounting\Exception\RangeException;

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
class SIE
{
    /**
     * End of line chars used
     */
    const EOL = "\r\n";

    /**
     * @var string Name of program generating SIE
     */
    private $program = "byrokrat_SIE";

    /**
     * @var string Version of program generating SIE
     */
    private $version = '1.0';

    /**
     * @var string Name of person (instance) generating SIE
     */
    private $creator = 'byrokrat_SIE';

    /**
     * @var string Name of company whose verifications are beeing handled
     */
    private $company = "";

    /**
     * @var \DateTime Start of accounting year
     */
    private $yearStart;

    /**
     * @var \DateTime End of accounting year
     */
    private $yearStop;

    /**
     * @var \DateTime Creation date
     */
    private $date;

    /**
     * @var string Type of chart of accounts used
     */
    private $typeOfChart = "EUBAS97";

    /**
     * @var array Loaded verifications
     */
    private $verifications = array();

    /**
     * @var array List of accounts used in loaded verifications
     */
    private $usedAccounts = array();

    /**
     * Construct
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Clear added verifications
     *
     * @return void
     */
    public function clear()
    {
        $this->verifications = array();
    }

    /**
     * Set name of generating program
     *
     * @param  string $program
     * @param  string $version
     * @return SIE instance for chaining
     */
    public function setProgram($program, $version)
    {
        assert('is_string($program)');
        assert('is_string($version)');
        $this->program = $program;
        $this->version = $version;

        return $this;
    }

    /**
     * Set creator name (normally logged in user or simliar)
     *
     * @param  string $creator
     * @return SIE instance for chaining
     */
    public function setCreator($creator)
    {
        assert('is_string($creator)');
        $this->creator = $creator;

        return $this;
    }

    /**
     * Set name of company whose verifications are beeing handled
     *
     * @param  string $company
     * @return SIE instance for chaining
     */
    public function setCompany($company)
    {
        assert('is_string($company)');
        $this->company = $company;

        return $this;
    }

    /**
     * Set accounting year
     *
     * @param  \DateTime $start Only date part is used
     * @param  \DateTime $stop Only date part is used
     * @return SIE instance for chaining
     */
    public function setYear(\DateTime $start, \DateTime $stop)
    {
        $start->setTime(0, 0, 0);
        $stop->setTime(23, 59, 59);
        $this->yearStart = $start;
        $this->yearStop = $stop;

        return $this;
    }

    /**
     * Set type of chart of accounts used
     *
     * @param  string $typeOfChart
     * @return SIE instance for chaining
     */
    public function setTypeOfChart($typeOfChart)
    {
        assert('is_string($typeOfChart)');
        $this->typeOfChart = $typeOfChart;

        return $this;
    }

    /**
     * Add verification to SIE, verification MUST be balanced
     *
     * @param  Verification             $ver
     * @throws UnexpectedValueException If $ver is unbalanced
     * @throws OutOfBoundsException     If $ver date is out of bounds
     * @return SIE Instance for chaining
     */
    public function addVerification(Verification $ver)
    {
        // Verify that verification is balanced
        if (!$ver->isBalanced()) {
            throw new UnexpectedValueException("Verification <{$ver->getText()}> is not balanced");
        }

        // Verify that verification date matches accounting year
        if (isset($this->yearStart)) {
            $verdate = $ver->getDate();
            if ($verdate < $this->yearStart || $verdate > $this->yearStop) {
                $date = $verdate->format('Y-m-d');
                throw new OutOfBoundsException("Verification date <$date> is out of bounds");
            }
        }

        // Set names of used accounts
        foreach ($ver->getAccounts() as $account) {
            $nr = $account->getNumber();
            $this->usedAccounts[$nr] = $account;
        }

        // Save verification
        $this->verifications[] = $ver;

        return $this;
    }

    /**
     * Remove control characters, addslashes and quote $str
     *
     * @param  string $str
     * @return string
     */
    public static function quote($str)
    {
        assert('is_string($str)');
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
        // Generate header
        $program = self::quote($this->program);
        $version = self::quote($this->version);
        $creator = self::quote($this->creator);
        $company = self::quote($this->company);
        $chartType = self::quote($this->typeOfChart);

        $sie = "#FLAGGA 0" . self::EOL;
        $sie .= "#PROGRAM $program $version" . self::EOL;
        $sie .= "#FORMAT PC8" . self::EOL;
        $sie .= "#GEN {$this->date->format('Ymd')} $creator" . self::EOL;
        $sie .= "#SIETYP 4" . self::EOL;
        $sie .= "#FNAMN $company" . self::EOL;
        $sie .= "#KPTYP $chartType" . self::EOL;

        if (isset($this->yearStart)) {
            $start = $this->yearStart->format('Ymd');
            $stop = $this->yearStop->format('Ymd');
            $sie .= "#RAR 0 $start $stop" . self::EOL;
        }

        $sie .= self::EOL;

        // Generate accounts
        foreach ($this->usedAccounts as $account) {
            $number = self::quote($account->getNumber());
            $name = self::quote($account->getName());
            $type = self::quote($this->translateAccountType($account));
            $sie .= "#KONTO $number $name" . self::EOL;
            $sie .= "#KTYP $number $type" . self::EOL;
        }

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
        $sie = iconv("UTF-8", "CP437", $sie);

        return $sie;
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
            $number = self::quote($account->getNumber());
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
     * @throws RangeException If $sie is not valid
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
                        throw new RangeException("Invalid chart type at line $nr");
                    }
                    // TODO not supported at the moment
                    // $accounts->setChartType($data[1]);
                    break;
                case '#KONTO':
                    // Account must have form #KONTO number name
                    if (!isset($data[2])) {
                        throw new RangeException("Invalid account values at line $nr");
                    }
                    $current = array($data[1], $data[2]);
                    break;
                case '#KTYP':
                    // Account type must have form #KTYP number type
                    if (!isset($data[2])) {
                        throw new RangeException("Invalid account values at line $nr");
                    }
                    // Type must referer to current account
                    if ($data[1] != $current[0]) {
                        throw new RangeException("Unexpected account type at line $nr");
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
            throw new RangeException("Account type missing for '{$current[0]}'");
        }

        return $accounts;
    }
}
