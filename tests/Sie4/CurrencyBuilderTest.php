<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\amount\Currency;
use Psr\Log\LoggerInterface;

/**
 * @covers \byrokrat\accounting\Sie4\CurrencyBuilder
 */
class CurrencyBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMoney()
    {
        $currencyBuilder = new CurrencyBuilder(
            $this->prophesize(LoggerInterface::CLASS)->reveal()
        );

        $this->assertEquals(
            new Currency\SEK('100'),
            $currencyBuilder->createMoney('100'),
            'SEK should be the default currency'
        );

        $currencyBuilder->setCurrencyClass('EUR');

        $this->assertEquals(
            new Currency\EUR('100'),
            $currencyBuilder->createMoney('100'),
            'Setting the currency to EUR should work'
        );
    }

    public function testUnvalidCurrencyClass()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $currencyBuilder = new CurrencyBuilder($logger->reveal());

        $currencyBuilder->setCurrencyClass('not-a-valid-currency');

        $logger->warning('Unknown currency not-a-valid-currency')->shouldHaveBeenCalled();

        $this->assertEquals(
            new Currency\SEK('100'),
            $currencyBuilder->createMoney('100')
        );
    }
}
