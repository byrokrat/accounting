<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\utils\PropheciesTrait;
use byrokrat\accounting\Dimension;

/**
 * @covers \byrokrat\accounting\Sie4\Helper\DimensionHelper
 */
class DimensionHelperTest extends \PHPUnit_Framework_TestCase
{
    use PropheciesTrait;

    /**
     * @var Object Created in setup()
     */
    private $dimensionHelper;

    public function setup()
    {
        $this->dimensionHelper = $this->getMockForTrait(DimensionHelper::CLASS);
    }

    public function testCreateAndGetDimension()
    {
        $this->assertSame(
            $this->dimensionHelper->onDim(1, 'foobar'),
            $this->dimensionHelper->getDimension(1)
        );
    }

    public function testCreateAndGetChildDimension()
    {
        $parent = $this->dimensionHelper->onDim(1, 'parent');

        $this->assertSame(
            $this->dimensionHelper->onUnderdim(2, 'child', 1),
            $this->dimensionHelper->getDimension(2)
        );

        $this->assertSame(
            $parent,
            $this->dimensionHelper->getDimension(2)->getParent()
        );
    }

    public function testCreateAndGetObject()
    {
        $parent = $this->dimensionHelper->onDim(1, 'parent');

        $this->assertSame(
            $this->dimensionHelper->onObjekt(1, 2, 'object'),
            $this->dimensionHelper->getObject(1, 2)
        );

        $this->assertSame(
            $parent,
            $this->dimensionHelper->getObject(1, 2)->getParent()
        );
    }

    public function testGetUnspecifiedObject()
    {
        $this->dimensionHelper->expects($this->atLeastOnce())
            ->method('registerError')
            ->with($this->anything());

        $this->assertSame(
            'UNSPECIFIED',
            $this->dimensionHelper->getObject(1, 2)->getDescription()
        );
    }

    public function testGetUnspecifiedDimension()
    {
        $this->dimensionHelper->expects($this->atLeastOnce())
            ->method('registerError')
            ->with($this->anything());

        $this->assertSame(
            'UNSPECIFIED',
            $this->dimensionHelper->getDimension(100)->getDescription()
        );
    }

    public function testGetUnspecifiedReservedDimension()
    {
        $this->dimensionHelper->expects($this->atLeastOnce())
            ->method('registerError')
            ->with($this->anything());

        $this->assertSame(
            'Anställd',
            $this->dimensionHelper->getDimension(7)->getDescription()
        );
    }

    public function testGetUnspecifiedReservedCostDimension()
    {
        $this->dimensionHelper->expects($this->atLeastOnce())
            ->method('registerError')
            ->with($this->anything());

        $dim = $this->dimensionHelper->getDimension(2);

        $this->assertSame(
            'Kostnadsbärare',
            $dim->getDescription()
        );

        $this->assertSame(
            $this->dimensionHelper->getDimension(1),
            $dim->getParent()
        );
    }
}
