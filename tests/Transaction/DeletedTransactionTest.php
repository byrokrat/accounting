<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\utils;
use byrokrat\accounting\Account;
use byrokrat\amount\Amount;

class DeletedTransactionTest extends \PHPUnit\Framework\TestCase
{
    use utils\PropheciesTrait;

    public function testIsDeleted()
    {
        $this->assertTrue(
            (new DeletedTransaction(
                $this->createMock(Account::CLASS),
                $this->createMock(Amount::CLASS),
                ''
            ))->isDeleted()
        );
    }

    public function testMandatorySignature()
    {
        $trans = new DeletedTransaction(
            $this->createMock(Account::CLASS),
            $this->createMock(Amount::CLASS),
            'sign'
        );

        $this->assertSame('sign', $trans->getSignature());
    }

    public function testCastToString()
    {
        $this->assertRegExp(
            '/^\(DELETED\)/',
            (string)(new DeletedTransaction(
                $this->prophesizeAccount()->reveal(),
                $this->prophesizeAmount()->reveal(),
                ''
            ))
        );
    }
}
