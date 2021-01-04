<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Writer;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\DebtAccount;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\accounting\Verification\Verification;
use byrokrat\amount\Amount;

/**
 * @covers \byrokrat\accounting\Sie4\Writer\Sie4Writer
 */
class Sie4WriterTest extends \PHPUnit\Framework\TestCase
{
    public function testGenerate()
    {
        $container = new Container(
            new Verification(
                transactions: [
                    new Transaction(amount: new Amount('100'), account: new DebtAccount('1000')),
                    new Transaction(amount: new Amount('-100'), account: new DebtAccount('1000')),
                ]
            )
        );

        $this->assertIsString((new Sie4Writer())->generateSie($container));
    }
}
