<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

class VerificationTest extends \PHPUnit_Framework_TestCase
{
    private function createTransaction(Amount $amount = null, Account $account = null)
    {
        $transaction = $this->prophesize(Transaction::CLASS);

        $transaction->getAmount()->willReturn(
            $amount ?: $this->prophesize(Amount::CLASS)->reveal()
        );

        $transaction->getAccount()->willReturn(
            $account ?: $this->prophesize(Account::CLASS)->reveal()
        );

        return $transaction->reveal();
    }

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
            $this->createTransaction(),
            $this->createTransaction(),
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
            $this->createTransaction(null, $a1920->reveal()),
            $this->createTransaction(null, $a1920->reveal()),
            $this->createTransaction(null, $a3000->reveal())
        );

        $accounts = $verification->getAccounts();

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
            $this->createTransaction(new Amount('100')),
            $this->createTransaction(new Amount('200')),
            $this->createTransaction(new Amount('-300'))
        );

        $this->assertTrue($verification->isBalanced());
    }

    public function testNegativeVerification()
    {
        $verification = new Verification('');
        $verification->addTransaction(
            $this->createTransaction(new Amount('200')),
            $this->createTransaction(new Amount('-300'))
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
            $this->createTransaction(new Amount('200')),
            $this->createTransaction(new Amount('-100'))
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
            $this->createTransaction(new SEK('200')),
            $this->createTransaction(new SEK('-100'))
        );

        $this->assertFalse($verification->isBalanced());
        $this->assertEquals(
            new SEK('100'),
            $verification->getDifference()
        );
    }
}
