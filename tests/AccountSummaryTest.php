<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class AccountSummaryTest extends BaseTestCase
{
    public function testExceptionOnInvalidAccount()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);

        $summary = new AccountSummary(
            $this->getAccountMock(1234, 'foo'),
            new Amount('0')
        );

        $summary->addTransaction(
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

        $summary = new AccountSummary($this->getAccountMock(0, '', true), new Amount('0'), $transactions);

        $this->assertSame(
            $transactions,
            $summary->getTransactions()
        );

        $summary->addTransaction($transaction);
    }

    public function testGetOutgoingBalance()
    {
        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->getSum()->willReturn(new Amount('-100'));
        $transactions = $transactions->reveal();

        $summary = new AccountSummary($this->getAccountMock(), new Amount('400'), $transactions);

        $this->assertEquals(
            new Amount('300'),
            $summary->getOutgoingBalance()
        );
    }

    public function decoratedMethodsProvider()
    {
        return [
            ['getNumber'],
            ['getName'],
            ['equals', $this->getAccountMock()],
            ['isAsset'],
            ['isCost'],
            ['isDebt'],
            ['isEarning'],
            ['getType'],
        ];
    }

    /**
     * @dataProvider decoratedMethodsProvider
     */
    public function testDecoration($method, $argument = null)
    {
        $decorated = $this->prophesize(Account::CLASS);
        $decorated->$method($argument)->shouldBeCalled();

        (new AccountSummary($decorated->reveal(), new Amount('0')))->$method($argument);
    }
}
