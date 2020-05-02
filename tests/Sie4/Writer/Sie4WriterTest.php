<?php

declare(strict_types = 1);

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
                1,
                new \DateTimeImmutable,
                new \DateTimeImmutable,
                'desc',
                'signature',
                new Transaction(
                    1,
                    new \DateTimeImmutable,
                    'desc',
                    'signature',
                    new Amount('100'),
                    new Amount('0'),
                    new DebtAccount('1000')
                )
            )
        );

        $this->assertIsString((new Sie4Writer)->generateSie($container));
    }
}
