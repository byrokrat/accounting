<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\amount\Amount;

class AddedTransactionTest extends \PHPUnit\Framework\TestCase
{
    public function testIsAdded()
    {
        $this->assertTrue(
            (new AddedTransaction(
                0,
                new \DateTimeImmutable(),
                '',
                '',
                new Amount('0'),
                new Amount('0'),
                $this->createMock(AccountInterface::class),
                []
            ))->isAdded()
        );
    }
}
