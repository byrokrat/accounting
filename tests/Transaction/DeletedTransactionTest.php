<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\amount\Amount;

class DeletedTransactionTest extends \PHPUnit\Framework\TestCase
{
    public function testIsDeleted()
    {
        $this->assertTrue(
            (new DeletedTransaction(
                0,
                new \DateTimeImmutable(),
                '',
                '',
                new Amount('0'),
                new Amount('0'),
                $this->createMock(AccountInterface::class),
                []
            ))->isDeleted()
        );
    }
}
