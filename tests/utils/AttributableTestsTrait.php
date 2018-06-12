<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Exception\LogicException;

trait AttributableTestsTrait
{
    abstract protected function getObjectToTest();

    public function testAttributes()
    {
        $obj = $this->getObjectToTest();

        $key = '___attribute-assumed-not-to-be-set___';
        $value = 'foobarbaz';

        $this->assertFalse($obj->hasAttribute($key));

        $obj->setAttribute($key, $value);

        $this->assertTrue($obj->hasAttribute($key));

        $this->assertSame(
            $value,
            $obj->getAttribute($key),
            'Getting a set attribute should return it'
        );

        $this->assertSame(
            $value,
            $obj->getAttribute(str_replace('a', 'A', $key)),
            'Getting a set attribute should base case-insensitive'
        );

        $this->assertSame(
            $value,
            $obj->getAttributes()[$key],
            'Getting all attributes should return attribute i small case'
        );
    }

    public function testExceptionWhenAttributeNotSet()
    {
        $this->expectException(LogicException::CLASS);
        $this->getObjectToTest()->getAttribute('this-is-not-set');
    }
}
