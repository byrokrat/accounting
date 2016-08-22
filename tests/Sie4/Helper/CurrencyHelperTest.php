<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\utils\PropheciesTrait;
use byrokrat\amount\Currency;

/**
 * @covers \byrokrat\accounting\Sie4\Helper\CurrencyHelper
 */
class CurrencyHelperTest extends \PHPUnit_Framework_TestCase
{
    use PropheciesTrait;

    public function testCreatingMoney()
    {
        $currencyHelper = $this->getMockForTrait(CurrencyHelper::CLASS);

        $this->assertEquals(
            new Currency\SEK('100'),
            $currencyHelper->createMoney('100'),
            'SEK should be the default currency'
        );

        $currencyHelper->onValuta('EUR');

        $this->assertEquals(
            new Currency\EUR('100'),
            $currencyHelper->createMoney('100'),
            'Setting the currency to EUR should work'
        );
    }

    public function testUnvalidCurrency()
    {
        $currencyHelper = $this->getMockForTrait(CurrencyHelper::CLASS);

        $currencyHelper->expects($this->once())
            ->method('registerWarning')
            ->with($this->equalTo('Unknown currency not-a-valid-currency'));

        $currencyHelper->onValuta('not-a-valid-currency');

        $this->assertEquals(
            new Currency\SEK('100'),
            $currencyHelper->createMoney('100')
        );
    }
}
