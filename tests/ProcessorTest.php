<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Currency\SEK;

class ProcessorTest extends \PHPUnit\Framework\TestCase
{
    public function testProcessContainer()
    {
        $account = new Account\Cost('1000');
        $dimension = new Dimension('2000');

        $container = new Container(
            new Transaction($account, new SEK('100'), null, $dimension),
            new Transaction($account, new SEK('100'), null, $dimension)
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new SEK('200'),
            $account->getSummary()->getOutgoingBalance()
        );

        $this->assertEquals(
            new SEK('200'),
            $dimension->getSummary()->getOutgoingBalance()
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new SEK('200'),
            $account->getSummary()->getOutgoingBalance()
        );
    }
}
