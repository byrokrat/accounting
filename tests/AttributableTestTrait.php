<?php

declare(strict_types=1);

namespace byrokrat\accounting;

trait AttributableTestTrait
{
    abstract protected function getAttributableToTest(): AttributableInterface;

    public function testAttributes()
    {
        $attributable = $this->getAttributableToTest();

        $key = '___attribute-assumed-not-to-be-set___';
        $value = 'foobarbaz';

        $this->assertFalse($attributable->hasAttribute($key));

        $this->assertSame(
            'default',
            $attributable->getAttribute($key, 'default'),
            'Reading an attribute that does not exist should return the default'
        );

        $attributable->setAttribute($key, $value);

        $this->assertTrue($attributable->hasAttribute($key));

        $this->assertSame(
            $value,
            $attributable->getAttribute($key),
            'Getting a set attribute should return it'
        );

        $this->assertSame(
            $value,
            $attributable->getAttribute(str_replace('a', 'A', $key)),
            'Getting a set attribute should base case-insensitive'
        );

        $this->assertSame(
            $value,
            $attributable->getAttributes()[$key],
            'Getting all attributes should return attribute i small case'
        );
    }
}
