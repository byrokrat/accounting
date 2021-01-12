<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidDateException;

class AccountingDateTest extends \PHPUnit\Framework\TestCase
{
    public function testSieString()
    {
        $date = AccountingDate::fromString('20210112');

        $this->assertSame('20210112', $date->formatSie4());
    }

    public function testCreateFromPrettyString()
    {
        $date = AccountingDate::fromString('2021-01-12');

        $this->assertSame('20210112', $date->formatSie4());
    }

    public function testCompareDifferentDays()
    {
        $older = AccountingDate::fromString('1982-02-23');
        $newer = AccountingDate::fromString('2021-01-12');

        $this->assertFalse($older->isAfter($newer));
        $this->assertTrue($newer->isAfter($older));

        $this->assertTrue($older->isBefore($newer));
        $this->assertFalse($newer->isBefore($older));

        $this->assertFalse($newer->isEqualTo($older));
        $this->assertFalse($older->isEqualTo($newer));

        $this->assertLessThan(0, $older->compare($newer));
        $this->assertGreaterThan(0, $newer->compare($older));
    }

    public function testCompareSameDay()
    {
        $dateTime = new \DateTimeImmutable();

        $dateA = AccountingDate::fromString($dateTime->format('Ymd'));
        $dateB = AccountingDate::fromDateTime($dateTime);

        $this->assertFalse($dateA->isAfter($dateB));
        $this->assertFalse($dateB->isAfter($dateA));

        $this->assertFalse($dateA->isBefore($dateB));
        $this->assertFalse($dateB->isBefore($dateA));

        $this->assertTrue($dateB->isEqualTo($dateA));
        $this->assertTrue($dateA->isEqualTo($dateB));

        $this->assertSame(0, $dateA->compare($dateB));
        $this->assertSame(0, $dateB->compare($dateA));
    }

    public function testCreateToday()
    {
        $older = AccountingDate::fromString('1982-02-23');
        $newer = AccountingDate::today();

        $this->assertTrue($older->isBefore($newer));
    }

    public function testTodayAlwaysYieldsTheSameObject()
    {
        $dateA = AccountingDate::today();
        $dateB = AccountingDate::today();

        $this->assertSame($dateA->getDateTime(), $dateB->getDateTime());
    }

    public function testEmptyStringYieldsToday()
    {
        $dateA = AccountingDate::today();
        $dateB = AccountingDate::fromString('');

        $this->assertSame($dateA->getDateTime(), $dateB->getDateTime());
    }

    public function testCreateFromMutable()
    {
        $dateA = AccountingDate::fromString('20210112');
        $dateB = AccountingDate::fromDateTime(new \DateTime('20210112'));

        $this->assertTrue($dateA->isEqualTo($dateB));
    }

    public function testExceptionOnMalformedDate()
    {
        $this->expectException(InvalidDateException::class);
        AccountingDate::fromString('this-is-not-a-valid-date');
    }
}
