<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class AccountBalanceTest extends BaseTestCase
{
    public function testExceptionOnInvalidAccount()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);

        $balance = new AccountBalance(
            $this->getAccountMock(1234, 'foo'),
            new Amount('0')
        );

        $balance->addTransaction(
            $this->getTransactionMock(
                new Amount('0'),
                $this->getAccountMock(9999, 'bar')
            )
        );
    }

    public function testTransactions()
    {
        $transaction = $this->getTransactionMock();

        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->addTransaction($transaction)->shouldBeCalled();
        $transactions = $transactions->reveal();

        $balance = new AccountBalance($this->getAccountMock(0, '', true), new Amount('0'), $transactions);

        $this->assertSame(
            $transactions,
            $balance->getTransactions()
        );

        $balance->addTransaction($transaction);
    }

    public function testGetOutgoingBalance()
    {
        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->getSum()->willReturn(new Amount('-100'));
        $transactions = $transactions->reveal();

        $balance = new AccountBalance($this->getAccountMock(), new Amount('400'), $transactions);

        $this->assertEquals(
            new Amount('300'),
            $balance->getOutgoingBalance()
        );
    }
}
