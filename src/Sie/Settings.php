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

/**
 * Value object for storing sie-file settings
 *
 * TODO Bryt ut SettingsInterface så att andra implementeringar kan göras...
 * TODO Ska vara getAccountingYearFirstDay(), getAccountingYearLastDay()
 * TODO Ska chartType verkligen vara här??
 * TODO getTargetCompany() istället för bara getCompany()
 */
class Settings
{
    /**
     * @var \DateTimeImmutable[] First and last days of accounting year
     */
    private $accountingYear;

    /**
     * @var string Name of program generating SIE
     */
    private $program = "byrokrat-accounting";

    /**
     * @var string Version of program generating SIE
     */
    private $version = 'unknown';

    /**
     * @var string Name of person (instance) generating SIE
     */
    private $creator = 'byrokrat-accounting';

    /**
     * @var string Name of company whose verifications are beeing handled
     */
    private $company = "";

    /**
     * @var string Type of chart of accounts used
     */
    private $chartType = "EUBAS97";

    /**
     * Set default accounting year at construct
     *
     * @param \DateTimeImmutable $firstDay First day of accounting year
     * @param \DateTimeImmutable $lastDay  Last day of accounting year
     */
    public function __construct(\DateTimeImmutable $firstDay = null, \DateTimeImmutable $lastDay = null)
    {
        $this->setAccountingYear(
            $firstDay ?: \DateTimeImmutable::createFromFormat('Y-m-d', date('Y').'-01-01'),
            $lastDay ?: \DateTimeImmutable::createFromFormat('Y-m-d', date('Y').'-12-31')
        );
    }

    /**
     * Set first and last days of accounting year
     *
     * @param  \DateTimeImmutable $firstDay First day of accounting year
     * @param  \DateTimeImmutable $lastDay  Last day of accounting year
     * @return self Instance to enable chaining
     */
    public function setAccountingYear(\DateTimeImmutable $firstDay, \DateTimeImmutable $lastDay): self
    {
        $firstDay->setTime(0, 0, 0);
        $lastDay->setTime(23, 59, 59);
        $this->accountingYear = [$firstDay, $lastDay];
        return $this;
    }

    /**
     * Get first and last days of accounting year
     *
     * @return \DateTimeImmutable[]
     */
    public function getAccountingYear(): array
    {
        return $this->accountingYear;
    }

    /**
     * Set name of generating program
     *
     * @param  string $program Name of program generating SIE
     * @return self   Instance to enable chaining
     */
    public function setProgram(string $program): self
    {
        $this->program = $program;
        return $this;
    }

    /**
     * Get name of generating program
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * Set version of generating program
     *
     * @param  string $version Version of program generating SIE
     * @return self   Instance to enable chaining
     */
    public function setProgramVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Get version of generating program
     */
    public function getProgramVersion(): string
    {
        return $this->version;
    }

    /**
     * Set creator name (normally logged in user or simliar)
     *
     * @param  string $creator Name of person (instance) generating SIE
     * @return self   Instance to enable chaining
     */
    public function setCreator(string $creator): self
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * Get creator name (normally logged in user or simliar)
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * Set name of company whose verifications are beeing handled
     *
     * @param  string $company Name of company whose verifications are beeing handled
     * @return self   Instance to enable chaining
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * Get name of company whose verifications are beeing handled
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Set type of chart of accounts used
     *
     * @param  string $chartType Type of chart of accounts used
     * @return self   Instance to enable chaining
     */
    public function setChartType(string $chartType): self
    {
        $this->chartType = $chartType;
        return $this;
    }

    /**
     * Get type of chart of accounts used
     */
    public function getChartType(): string
    {
        return $this->chartType;
    }
}
