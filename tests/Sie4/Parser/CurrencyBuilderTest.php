<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\amount\Currency;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\CurrencyBuilder
 */
class CurrencyBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateMoney()
    {
        $currencyBuilder = new CurrencyBuilder(
            $this->prophesize(Logger::CLASS)->reveal()
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
        $logger = $this->prophesize(Logger::CLASS);

        $currencyBuilder = new CurrencyBuilder($logger->reveal());

        $currencyBuilder->setCurrencyClass('not-a-valid-currency');

        $logger->log('warning', 'Unknown currency not-a-valid-currency')->shouldHaveBeenCalled();

        $this->assertEquals(
            new Currency\SEK('100'),
            $currencyBuilder->createMoney('100')
        );
    }
}
