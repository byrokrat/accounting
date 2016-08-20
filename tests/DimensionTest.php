<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class DimensionTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait;

    public function testAttributable()
    {
        $this->assertAttributable(new Dimension(0, ''));
    }

    public function testDescribable()
    {
        $this->assertDescribable(
            'foobar',
            new Dimension(0, 'foobar')
        );
    }

    public function testGetNumber()
    {
        $this->assertSame(
            1234,
            (new Dimension(1234, ''))->getNumber()
        );
    }

    public function testParent()
    {
        $parent = new Dimension(0, '');
        $child = new Dimension(0, '', $parent);

        $this->assertTrue($child->hasParent());

        $this->assertSame(
            $parent,
            $child->getParent()
        );

        $this->assertSame(
            [$parent],
            $child->query()->toArray()
        );
    }

    public function testNoParent()
    {
        $dim = new Dimension(0, '');

        $this->assertFalse($dim->hasParent());

        $this->assertSame(
            [],
            $dim->query()->toArray()
        );
    }

    public function testExceptionWhenNoParentIsSet()
    {
        $this->setExpectedException(Exception\LogicException::CLASS);
        (new Dimension(0, ''))->getParent();
    }

    public function testExceptionOnInvalidArgumentInDimension()
    {
        $this->setExpectedException(Exception\LogicException::CLASS);
        (new Dimension(0, ''))->inDimension('not-an-int-or-dimension');
    }

    public function testInDimension()
    {
        $dim = new Dimension(
            0,
            'outer',
            new Dimension(
                1,
                'middle',
                new Dimension(
                    2,
                    'inner'
                )
            )
        );

        $this->assertFalse($dim->inDimension(0));
        $this->assertTrue($dim->inDimension(1));
        $this->assertTrue($dim->inDimension(2));
        $this->assertTrue($dim->inDimension(new Dimension(2, '')));
        $this->assertFalse($dim->inDimension(3));
    }
}
