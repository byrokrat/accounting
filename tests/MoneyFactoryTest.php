<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidAmountException;
use Money\Currency;
use Money\Money;

class MoneyFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionOnMalformedAmount()
    {
        $this->expectException(InvalidAmountException::class);
        $factory = new MoneyFactory(new Currency('SEK'));
        $factory->createMoney('this-is-not-valid');
    }

    public function testExceptionOnToManySubunits()
    {
        $this->expectException(InvalidAmountException::class);
        $factory = new MoneyFactory(new Currency('SEK'));
        $factory->createMoney('1.001');
    }

    public function testCreateMoney()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('99900'),
            $factory->createMoney('999')
        );
    }

    public function testCreateMoneyFromExplicitZeroSubunits()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('99900'),
            $factory->createMoney('999.00')
        );
    }

    public function testCreateMoneyFromSimgleSubunit()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('99910'),
            $factory->createMoney('999.1')
        );
    }

    public function testCreateMoneyFromTwoSubunit()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('99955'),
            $factory->createMoney('999.55')
        );
    }

    public function testCreateNegativeMoney()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('-99900'),
            $factory->createMoney('-999')
        );
    }

    public function testCreateNegativeMoneyWithSubunits()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('-99911'),
            $factory->createMoney('-999.11')
        );
    }

    public function testCreateMoneyWithLeadingZeros()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('99900'),
            $factory->createMoney('00999')
        );
    }

    public function testCreateNegativeMoneyWithLeadingZerosAndSubunits()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('-99910'),
            $factory->createMoney('-00999.1')
        );
    }

    public function testCreateMoneyWithUnlySubunits()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('1'),
            $factory->createMoney('0.01')
        );
    }

    public function testCreateNegativeMoneyWithUnlySubunits()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('-10'),
            $factory->createMoney('-0.1')
        );
    }

    public function testCreateZeroMoney()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $this->assertEquals(
            Money::SEK('0'),
            $factory->createMoney('0')
        );
    }

    public function testSetCurrency()
    {
        $factory = new MoneyFactory(new Currency('SEK'));

        $factory->setCurrency(new Currency('EUR'));

        $this->assertEquals(
            Money::EUR('100'),
            $factory->createMoney('1')
        );
    }

    public function testDefaultCurrency()
    {
        $factory = new MoneyFactory();

        $this->assertEquals(
            Money::SEK('100'),
            $factory->createMoney('1')
        );
    }
}
