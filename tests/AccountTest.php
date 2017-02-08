<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnNonNumericAccountNumber()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        new Account\Asset('this-is-not-a-numerical-string');
    }

    public function testIsAsset()
    {
        $this->assertTrue(
            (new Account\Asset('1000'))->isAsset(),
            'Asset objects should identify themselves using isAsset'
        );
        $this->assertFalse(
            (new Account\Cost('1000'))->isAsset(),
            'Non-Asset objects should not identify themselves using isAsset'
        );
    }

    public function testIsCost()
    {
        $this->assertTrue(
            (new Account\Cost('1000'))->isCost(),
            'Cost objects should identify themselves using isCost'
        );
        $this->assertFalse(
            (new Account\Debt('1000'))->isCost(),
            'Non-Cost objects should not identify themselves using isCost'
        );
    }

    public function testIsDebt()
    {
        $this->assertTrue(
            (new Account\Debt('1000'))->isDebt(),
            'Debt objects should identify themselves using isDebt'
        );
        $this->assertFalse(
            (new Account\Earning('1000'))->isDebt(),
            'Non-Debt objects should not identify themselves using isDebt'
        );
    }

    public function testIsEarning()
    {
        $this->assertTrue(
            (new Account\Earning('1000'))->isEarning(),
            'Earning objects should identify themselves using isEarning'
        );
        $this->assertFalse(
            (new Account\Asset('1000'))->isEarning(),
            'Non-Earning objects should not identify themselves using isEarning'
        );
    }

    public function testSetAttributesAtConstruct()
    {
        $this->assertSame(
            'bar',
            (new Account\Asset('1234', '', ['foo' => 'bar']))->getAttribute('foo')
        );
    }

    public function testGetNumber()
    {
        $this->assertSame(
            1000,
            (new Account\Asset('1000'))->getNumber()
        );
    }

    public function testCastToString()
    {
        $this->assertSame(
            '1000 (desc)',
            (string)new Account\Asset('1000', 'desc')
        );
    }
}
