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

namespace byrokrat\accounting\Sie;

/**
 * Interface for reading SIE settings
 */
interface SettingsInterface
{
    /**
     * Get first day of accounting year
     */
    public function getAccountingYearFirstDay(): \DateTimeImmutable;

    /**
     * Get last day of accounting year
     */
    public function getAccountingYearLastDay(): \DateTimeImmutable;

    /**
     * Get name of generating program
     */
    public function getProgram(): string;

    /**
     * Get version of generating program
     */
    public function getProgramVersion(): string;

    /**
     * Get creator name (normally logged in user or simliar)
     */
    public function getCreator(): string;

    /**
     * Get name of company whose verifications are beeing handled
     */
    public function getTargetCompany(): string;

    /**
     * Get free text description
     */
    public function getDescription(): string;
}
