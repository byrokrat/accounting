<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Container;
use byrokrat\accounting\MoneyFactory;
use byrokrat\accounting\Query;
use Money\Currency;

/**
 * @covers \byrokrat\accounting\Template\TemplateRendererFactory
 */
class TemplateRendererFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateRendererDefaultCurrency()
    {
        $this->assertEquals(
            new TemplateRenderer(new Query([]), new MoneyFactory()),
            (new TemplateRendererFactory())->createRenderer(new Container())
        );
    }

    public function testCreateRendererSetCurrency()
    {
        $currency = new Currency('EUR');

        $this->assertEquals(
            new TemplateRenderer(new Query([]), new MoneyFactory($currency)),
            (new TemplateRendererFactory())->createRenderer(new Container(), $currency)
        );
    }
}
