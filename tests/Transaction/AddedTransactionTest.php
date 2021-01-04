<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\amount\Amount;

class AddedTransactionTest extends \PHPUnit\Framework\TestCase
{
    public function testIsAdded()
    {
        $trans = new AddedTransaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertTrue($trans->isAdded());
    }
}
