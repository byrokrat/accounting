<?php

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Exception\LogicException;

class DimensionTest extends \PHPUnit\Framework\TestCase
{
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Dimension('');
    }

    public function testGetId()
    {
        $this->assertSame(
            '1234',
            (new Dimension(id: '1234'))->getId()
        );
    }

    public function testDescription()
    {
        $this->assertSame(
            'foo',
            (new Dimension(id: '', description: 'foo'))->getDescription()
        );
    }

    public function testParent()
    {
        $parent = new Dimension(id: '');
        $child = new Dimension(id: '', parent: $parent);

        $this->assertTrue($child->hasParent());

        $this->assertSame(
            $parent,
            $child->getParent()
        );

        $this->assertSame(
            [$parent],
            $child->select()->asArray()
        );
    }

    public function testNoParent()
    {
        $dim = new Dimension('');

        $this->assertFalse($dim->hasParent());

        $this->assertSame(
            [],
            $dim->select()->asArray()
        );
    }

    public function testExceptionWhenNoParentIsSet()
    {
        $this->expectException(LogicException::class);
        (new Dimension(''))->getParent();
    }

    public function testInDimension()
    {
        $dim = new Dimension(
            id: '0',
            parent: new Dimension(
                id: '1',
                parent: new Dimension('2')
            )
        );

        $this->assertFalse($dim->inDimension('0'));
        $this->assertTrue($dim->inDimension('1'));
        $this->assertTrue($dim->inDimension('2'));
        $this->assertTrue($dim->inDimension(new Dimension('2')));
        $this->assertFalse($dim->inDimension('3'));
    }
}
