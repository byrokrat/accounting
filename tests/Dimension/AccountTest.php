<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\Exception\RuntimeException;

class AccountTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionOnNonNumericAccountNumber()
    {
        $this->expectException(RuntimeException::CLASS);
        new AssetAccount('this-is-not-a-numerical-string');
    }

    public function testIsAsset()
    {
        $this->assertTrue(
            (new AssetAccount('1000'))->isAsset(),
            'Asset objects should identify themselves using isAsset'
        );
        $this->assertFalse(
            (new CostAccount('1000'))->isAsset(),
            'Non-Asset objects should not identify themselves using isAsset'
        );
    }

    public function testIsCost()
    {
        $this->assertTrue(
            (new CostAccount('1000'))->isCost(),
            'Cost objects should identify themselves using isCost'
        );
        $this->assertFalse(
            (new DebtAccount('1000'))->isCost(),
            'Non-Cost objects should not identify themselves using isCost'
        );
    }

    public function testIsDebt()
    {
        $this->assertTrue(
            (new DebtAccount('1000'))->isDebt(),
            'Debt objects should identify themselves using isDebt'
        );
        $this->assertFalse(
            (new EarningAccount('1000'))->isDebt(),
            'Non-Debt objects should not identify themselves using isDebt'
        );
    }

    public function testIsEarning()
    {
        $this->assertTrue(
            (new EarningAccount('1000'))->isEarning(),
            'Earning objects should identify themselves using isEarning'
        );
        $this->assertFalse(
            (new AssetAccount('1000'))->isEarning(),
            'Non-Earning objects should not identify themselves using isEarning'
        );
    }

    public function testSetAttributesAtConstruct()
    {
        $this->assertSame(
            'bar',
            (new AssetAccount('1234', '', ['foo' => 'bar']))->getAttribute('foo')
        );
    }

    public function testGetId()
    {
        $this->assertSame(
            '1000',
            (new AssetAccount('1000'))->getId()
        );
    }
}
