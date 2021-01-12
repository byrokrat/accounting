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

use byrokrat\accounting\AccountingDate;

final class MetaData
{
    public AccountingDate $generationDate;

    public function __construct(
        public string $generatingProgram = 'byrokrat/accounting',
        public string $generatingProgramVersion = '2',
        AccountingDate | \DateTimeInterface $generationDate = null,
        public string $generatingUser = '',
        public string $companyName = '',
        public string $companyIdCode = '',
        public string $companyOrgNr = '',
        public string $description = '',
        public string $currency = '',
        public string $accountPlanType = '',
    ) {
        if ($generationDate instanceof \DateTimeInterface) {
            $generationDate = AccountingDate::fromDateTime($generationDate);
        }

        $this->generationDate = $generationDate ?: AccountingDate::today();
    }
}
