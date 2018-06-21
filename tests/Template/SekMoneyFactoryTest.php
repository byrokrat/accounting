<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Template;

use byrokrat\amount\Currency\SEK;

class SekMoneyFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateMoney()
    {
        $this->assertEquals(
            new SEK('999'),
            (new SekMoneyFactory)->createMoney('999')
        );
    }
}
