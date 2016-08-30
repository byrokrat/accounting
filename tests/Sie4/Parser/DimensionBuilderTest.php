<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension;
use Psr\Log\LoggerInterface;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\DimensionBuilder
 */
class DimensionBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateAndGetDimension()
    {
        $dimensionBuilder = new DimensionBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $dimensionBuilder->addDimension('1', 'foobar');

        $this->assertSame(
            $dimensionBuilder->getDimension('1'),
            $dimensionBuilder->getDimension('1')
        );

        $this->assertCount(1, $dimensionBuilder->getDimensions());
    }

    public function testCreateAndGetChildDimension()
    {
        $dimensionBuilder = new DimensionBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $dimensionBuilder->addDimension('1', 'parent');
        $dimensionBuilder->addDimension('2', 'child', '1');

        $this->assertSame(
            $dimensionBuilder->getDimension('1'),
            $dimensionBuilder->getDimension('2')->getParent()
        );
    }

    public function testCreateAndGetObject()
    {
        $dimensionBuilder = new DimensionBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $dimensionBuilder->addDimension('1', 'parent');
        $dimensionBuilder->addObject('1', '2', 'object');

        $this->assertSame(
            $dimensionBuilder->getDimension('1'),
            $dimensionBuilder->getObject('1', '2')->getParent()
        );

        $this->assertCount(2, $dimensionBuilder->getDimensions());
    }

    public function testGetUnspecifiedObject()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $dimensionBuilder = new DimensionBuilder(
            $logger->reveal()
        );

        $this->assertSame(
            'UNSPECIFIED',
            $dimensionBuilder->getObject('1', '2')->getDescription()
        );

        $logger->warning('Object number 1.2 not defined', ["_addToLineCount" => 1])->shouldHaveBeenCalled();
    }

    public function testGetUnspecifiedDimension()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $dimensionBuilder = new DimensionBuilder(
            $logger->reveal()
        );

        $this->assertSame(
            'UNSPECIFIED',
            $dimensionBuilder->getDimension('100')->getDescription()
        );

        $logger->warning('Dimension number 100 not defined', ["_addToLineCount" => 1])->shouldHaveBeenCalled();
    }

    public function testGetUnspecifiedReservedDimension()
    {
        $dimensionBuilder = new DimensionBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $this->assertSame(
            'Anställd',
            $dimensionBuilder->getDimension('7')->getDescription()
        );
    }

    public function testGetUnspecifiedReservedCostDimension()
    {
        $dimensionBuilder = new DimensionBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $dim = $dimensionBuilder->getDimension('2');

        $this->assertSame(
            'Kostnadsbärare',
            $dim->getDescription()
        );

        $this->assertSame(
            $dimensionBuilder->getDimension('1'),
            $dim->getParent()
        );
    }
}
