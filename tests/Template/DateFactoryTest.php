<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

class DateFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateDate()
    {
        $this->assertSame(
            '180621',
            (new DateFactory())->createDate('20180621')->format('ymd')
        );
    }

    public function testCreateNow()
    {
        $now = new \DateTimeImmutable();

        $this->assertSame(
            $now,
            (new DateFactory($now))->createDate('{now}')
        );
    }
}
