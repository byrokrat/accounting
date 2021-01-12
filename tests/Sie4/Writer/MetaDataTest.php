<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Writer;

use byrokrat\accounting\AccountingDate;

class MetaDataTest extends \PHPUnit\Framework\TestCase
{
    public function testGenerationDatePassed()
    {
        $date = AccountingDate::fromString('20200101');

        $this->assertSame(
            $date,
            (new MetaData(generationDate: $date))->generationDate
        );
    }

    public function testGenerationDateDefaultsToToday()
    {
        $this->assertSame(
            AccountingDate::today(),
            (new MetaData())->generationDate
        );
    }

    public function testGenerationDateFromMutable()
    {
        $this->assertEquals(
            AccountingDate::fromString('20200101'),
            (new MetaData(generationDate: new \DateTime('20200101')))->generationDate
        );
    }
}
