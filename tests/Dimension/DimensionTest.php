<?php

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Summary;
use byrokrat\amount\Amount;

class DimensionTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
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

    public function testChildren()
    {
        $child = new Dimension(id: '');
        $dim = new Dimension(id: '', children: [$child]);

        $this->assertTrue($dim->hasChildren());
        $this->assertSame([$child], $dim->getChildren());
        $this->assertSame([$child], $dim->getItems());
    }

    public function testNoChildren()
    {
        $dim = new Dimension('');

        $this->assertFalse($dim->hasChildren());
        $this->assertSame([], $dim->getChildren());
        $this->assertSame([], $dim->getItems());
    }

    public function testAttributesToConstructor()
    {
        $this->assertSame(
            'bar',
            (new Dimension(id: '0', attributes: ['foo' => 'bar']))->getAttribute('foo')
        );
    }

    public function testAddChild()
    {
        $child1 = new Dimension('child1');
        $child2 = new Dimension('child2');

        $dimension = new Dimension(
            id: 'parent',
            children: [$child1],
        );

        $dimension->addChild($child2);

        $this->assertSame([$child1, $child2], $dimension->getChildren());
    }

    public function testChildSummaryIncluded()
    {
        $child = $this->prophesize(DimensionInterface::class);
        $child->getSummary()->willReturn(Summary::fromAmount(new Amount('100')));
        $child = $child->reveal();

        $dim = new Dimension(id: '', children: [$child]);

        $this->assertTrue($dim->getSummary()->getDebitTotal()->equals(new Amount('100')));
    }
}
