<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Writer;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\Account;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\accounting\Verification\Verification;
use Money\Money;

/**
 * @covers \byrokrat\accounting\Sie4\Writer\Sie4iWriter
 */
class Sie4iWriterTest extends \PHPUnit\Framework\TestCase
{
    public function testGenerate()
    {
        $container = new Container(
            new Verification(
                transactions: [
                    new Transaction(amount: Money::SEK('100'), account: new Account('1000')),
                    new Transaction(amount: Money::SEK('-100'), account: new Account('1000')),
                ]
            )
        );

        $this->assertIsString((new Sie4iWriter())->generateSie($container));
    }
}
