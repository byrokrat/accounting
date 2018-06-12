<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Exception\LogicException;

trait DateTestsTrait
{
    abstract protected function getObjectToTest();

    public function testDate()
    {
        $obj = $this->getObjectToTest();

        $this->assertFalse($obj->hasDate());

        $date = new \DateTimeImmutable;

        $obj->setDate($date);

        $this->assertTrue($obj->hasDate());

        $this->assertSame($date, $obj->getDate());
    }

    public function testExceptionWhenDateNotSet()
    {
        $this->expectException(LogicException::CLASS);
        $this->getObjectToTest()->getDate();
    }
}
