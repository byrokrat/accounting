<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Exception\LogicException;

trait SignatureTestsTrait
{
    abstract protected function getObjectToTest();

    public function testSignature()
    {
        $obj = $this->getObjectToTest();

        $this->assertFalse($obj->hasSignature());

        $signature = 'foobar';

        $obj->setSignature($signature);

        $this->assertTrue($obj->hasSignature());
        $this->assertSame($signature, $obj->getSignature());
    }

    public function testExceptionWhenSignatureNotSet()
    {
        $this->expectException(LogicException::CLASS);
        $this->getObjectToTest()->getSignature();
    }
}
