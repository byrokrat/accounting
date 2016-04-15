<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class VerificationTest extends BaseTestCase
{
    public function testVerificationText()
    {
        $this->assertEquals(
            (new Verification('foobar'))->getText(),
            'foobar'
        );
    }

    public function testVerificationDate()
    {
        $now = new \DateTimeImmutable();

        $this->assertSame(
            $now,
            (new Verification('', $now))->getDate()
        );

        $this->assertTrue(
            (new Verification(''))->getDate() >= $now
        );
    }

    public function testTransactions()
    {
        $transaction = $this->getTransactionMock();

        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->addTransaction($transaction)->shouldBeCalled();
        $transactions = $transactions->reveal();

        $verification = new Verification('', new \DateTimeImmutable, $transactions);

        $this->assertSame(
            $transactions,
            $verification->getTransactions()
        );

        $verification->addTransaction($transaction);
    }

    public function testGetAccounts()
    {
        $accounts = $this->prophesize(AccountSet::CLASS)->reveal();

        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->getAccounts()->willReturn($accounts);

        $verification = new Verification('', new \DateTimeImmutable, $transactions->reveal());

        $this->assertSame(
            $accounts,
            $verification->getAccounts()
        );
    }

    public function testIsBalanced()
    {
        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->getSum()->willReturn(new Amount('0'));

        $verification = new Verification('', new \DateTimeImmutable, $transactions->reveal());

        $this->assertTrue(
            $verification->isBalanced()
        );
    }

    public function testIsNotBalanced()
    {
        $transactions = $this->prophesize(TransactionSet::CLASS);
        $transactions->getSum()->willReturn(new Amount('100'));

        $verification = new Verification('', new \DateTimeImmutable, $transactions->reveal());

        $this->assertFalse(
            $verification->isBalanced()
        );
    }
}
