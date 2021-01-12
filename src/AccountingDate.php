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

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidDateException;

final class AccountingDate
{
    private static AccountingDate $today;
    private \DateTimeImmutable $date;

    public static function fromDateTime(\DateTimeInterface $date): self
    {
        return new static(\DateTimeImmutable::createFromInterface($date));
    }

    public static function fromString(string $date): self
    {
        if (!$date) {
            return self::today();
        }

        if (!preg_match('/^\d{4}-?\d{2}-?\d{2}/', $date)) {
            throw new InvalidDateException("Invalid date $date, expecting yyyy-mm-dd or yyyymmdd");
        }

        return new static(new \DateTimeImmutable($date));
    }

    public static function today(): self
    {
        if (!isset(self::$today)) {
            self::$today = new static(new \DateTimeImmutable());
        }

        return self::$today;
    }

    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date->setTime(0, 0, 0);
    }

    public function formatSie4(): string
    {
        return $this->date->format('Ymd');
    }

    public function getDateTime(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function isAfter(AccountingDate $date): bool
    {
        return $this->date > $date->date;
    }

    public function isBefore(AccountingDate $date): bool
    {
        return $date->isAfter($this);
    }

    public function isEqualTo(AccountingDate $date): bool
    {
        return $this->date == $date->date;
    }

    public function compare(AccountingDate $date): int
    {
        return $this->date <=> $date->date;
    }
}
