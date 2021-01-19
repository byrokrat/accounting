<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use Money\Money;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\DimensionBuilder
 */
class DimensionBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testDefineDimension()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineDimension(id: '1234', description: 'foobar');

        $dimension = $dimensionBuilder->getDimension('1234');

        $this->assertSame('1234', $dimension->getId());
        $this->assertSame('foobar', $dimension->getDescription());
    }

    public function testGetUnspecifiedDimension()
    {
        $dimensionBuilder = new DimensionBuilder();

        $this->assertSame(
            $dimensionBuilder->getDimension('1'),
            $dimensionBuilder->getDimension('1')
        );

        $this->assertCount(1, $dimensionBuilder->getDimensions());
    }

    public function testDefineChildDimension()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineDimension(id: 'parent');
        $dimensionBuilder->defineDimension(id: 'child', parent:'parent');

        $this->assertSame(
            [$dimensionBuilder->getDimension('child')],
            $dimensionBuilder->getDimension('parent')->getChildren()
        );
    }

    public function testDefineObject()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineDimension(id: 'parent');
        $dimensionBuilder->defineObject(id: 'object', parent: 'parent');

        $this->assertSame(
            [$dimensionBuilder->getObject(id: 'object', parent: 'parent')],
            $dimensionBuilder->getDimension('parent')->getChildren()
        );

        $this->assertCount(2, $dimensionBuilder->getDimensions());
    }

    public function testGetUnspecifiedObject()
    {
        $dimensionBuilder = new DimensionBuilder();

        $this->assertSame(
            [$dimensionBuilder->getObject(id: 'object', parent: 'parent')],
            $dimensionBuilder->getDimension('parent')->getChildren()
        );
    }

    public function testGetUnspecifiedReservedDimension()
    {
        $dimensionBuilder = new DimensionBuilder();

        $this->assertSame(
            'Anställd',
            $dimensionBuilder->getDimension('7')->getDescription()
        );
    }

    public function testGetUnspecifiedReservedCostDimension()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dim = $dimensionBuilder->getDimension('2');

        $this->assertSame(
            'Kostnadsbärare',
            $dim->getDescription()
        );

        $this->assertSame(
            [$dim],
            $dimensionBuilder->getDimension('1')->getChildren(),
        );
    }

    /**
     * @depends testDefineDimension
     */
    public function testMultipleDimensionDefinitions()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineDimension(id: '1234', description: 'original');
        $dimensionBuilder->defineDimension(id: '1234', description: 'edited');
        $dimensionBuilder->defineDimension(id: '1234', parent: 'parent');

        $dimension = $dimensionBuilder->getDimension('1234');

        $this->assertSame(
            'original',
            $dimension->getDescription()
        );

        $this->assertSame(
            [$dimension],
            $dimensionBuilder->getDimension('parent')->getChildren(),
        );
    }

    public function testDefineIncomingBalance()
    {
        $dimensionBuilder = new DimensionBuilder();

        $money = Money::SEK('100');

        $dimensionBuilder->defineDimension(id: '1234', incomingBalance: $money);

        $dimension = $dimensionBuilder->getDimension('1234');

        $this->assertSame($money, $dimension->getSummary()->getIncomingBalance());
    }

    /**
     * @depends testDefineIncomingBalance
     */
    public function testIncomingBalanceIsPassedDuringDefine()
    {
        $dimensionBuilder = new DimensionBuilder();

        $money = Money::SEK('100');

        $dimensionBuilder->defineDimension(id: '1234', incomingBalance: $money);

        $dimensionBuilder->defineDimension(id: '1234', description: 'desc');

        $dimension = $dimensionBuilder->getDimension('1234');

        $this->assertSame($money, $dimension->getSummary()->getIncomingBalance());
    }

    public function testDefineAttributes()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineDimension(id: '1234', attributes: ['foo' => 'foo']);

        $dimensionBuilder->defineDimension(id: '1234', attributes: ['bar' => 'bar']);

        $dimensionBuilder->defineDimension(id: '1234', description: 'desc');

        $dimension = $dimensionBuilder->getDimension('1234');

        $this->assertSame(['foo' => 'foo', 'bar' => 'bar'], $dimension->getAttributes());
    }

    public function testDefineObjectIncomingBalance()
    {
        $dimensionBuilder = new DimensionBuilder();

        $money = Money::SEK('100');

        $dimensionBuilder->defineObject(id: '1234', parent: 'foo', incomingBalance: $money);

        $obj = $dimensionBuilder->getObject(id: '1234', parent: 'foo');

        $this->assertSame($money, $obj->getSummary()->getIncomingBalance());
    }

    public function testDefineObjectAttributes()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensionBuilder->defineObject(id: '1234', parent: 'foo', attributes: ['foo' => 'foo']);

        $dimensionBuilder->defineObject(id: '1234', parent: 'foo', attributes: ['bar' => 'bar']);

        $dimensionBuilder->defineObject(id: '1234', parent: 'foo', description: 'desc');

        $obj = $dimensionBuilder->getObject(id: '1234', parent: 'foo');

        $this->assertSame(['foo' => 'foo', 'bar' => 'bar'], $obj->getAttributes());
    }

    /**
     * @depends testGetUnspecifiedDimension
     */
    public function testGetDimensions()
    {
        $dimensionBuilder = new DimensionBuilder();

        $dimensions = [
            $dimensionBuilder->getDimension('1'),
            $dimensionBuilder->getObject('2', '1'),
        ];

        $this->assertSame($dimensions, $dimensionBuilder->getDimensions());
    }
}
