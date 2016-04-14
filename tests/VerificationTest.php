<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

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

    public function testGetTransactions()
    {
        $transactions = [
            $this->getTransactionMock(),
            $this->getTransactionMock(),
        ];

        $verification = new Verification('');
        $verification->addTransaction(...$transactions);

        $this->assertEquals(
            $transactions,
            $verification->getTransactions()
        );
    }

    public function testGetAccounts()
    {
        $a1920 = $this->prophesize(Account::CLASS);
        $a1920->getNumber()->willReturn(1920);

        $a3000 = $this->prophesize(Account::CLASS);
        $a3000->getNumber()->willReturn(3000);

        $verification = new Verification('');
        $verification->addTransaction(
            $this->getTransactionMock(null, $a1920->reveal()),
            $this->getTransactionMock(null, $a1920->reveal()),
            $this->getTransactionMock(null, $a3000->reveal())
        );

        $accounts = iterator_to_array($verification->getAccounts());

        $this->assertCount(
            2,
            $accounts,
            'Verification contains 2 unique accounts and this should be relflected in count'
        );

        $this->assertArrayHasKey(1920, $accounts);
        $this->assertArrayHasKey(3000, $accounts);
    }

    public function testBalancedVerification()
    {
        $verification = new Verification('');
        $verification->addTransaction(
            $this->getTransactionMock(new Amount('100')),
            $this->getTransactionMock(new Amount('200')),
            $this->getTransactionMock(new Amount('-300'))
        );

        $this->assertTrue($verification->isBalanced());
    }

    public function testNegativeVerification()
    {
        $verification = new Verification('');
        $verification->addTransaction(
            $this->getTransactionMock(new Amount('200')),
            $this->getTransactionMock(new Amount('-300'))
        );

        $this->assertFalse($verification->isBalanced());
        $this->assertEquals(
            new Amount('-100'),
            $verification->getDifference()
        );
    }

    public function testPositiveVerification()
    {
        $verification = new Verification('');
        $verification->addTransaction(
            $this->getTransactionMock(new Amount('200')),
            $this->getTransactionMock(new Amount('-100'))
        );

        $this->assertFalse($verification->isBalanced());
        $this->assertEquals(
            new Amount('100'),
            $verification->getDifference()
        );
    }

    public function testCurrency()
    {
        $verification = new Verification('');
        $verification->addTransaction(
            $this->getTransactionMock(new SEK('200')),
            $this->getTransactionMock(new SEK('-100'))
        );

        $this->assertFalse($verification->isBalanced());
        $this->assertEquals(
            new SEK('100'),
            $verification->getDifference()
        );
    }
}
