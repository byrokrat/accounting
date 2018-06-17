<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;
use Prophecy\Argument;

class VerificationTest extends \PHPUnit\Framework\TestCase
{
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', '');
    }

    public function testId()
    {
        $this->assertSame(
            1,
            (new Verification(1, new \DateTimeImmutable, new \DateTimeImmutable, '', ''))->getVerificationId()
        );
    }

    public function testDates()
    {
        $transactionDate = new \DateTimeImmutable;
        $registrationDate = new \DateTimeImmutable;

        $ver = new Verification(0, $transactionDate, $registrationDate, '', '');

        $this->assertSame($transactionDate, $ver->getTransactionDate());
        $this->assertSame($registrationDate, $ver->getRegistrationDate());
    }

    public function testDescription()
    {
        $this->assertSame(
            'desc',
            (new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, 'desc', ''))->getDescription()
        );
    }

    public function testSignature()
    {
        $this->assertSame(
            'sign',
            (new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', 'sign'))->getSignature()
        );
    }

    public function testAccessingTransactions()
    {
        $transA = $this->createMock(TransactionInterface::CLASS);
        $transB = $this->createMock(TransactionInterface::CLASS);

        $ver = new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', '', $transA, $transB);

        $this->assertSame(
            [$transA, $transB],
            $ver->getTransactions()
        );
    }

    public function transactionArithmeticsProvider()
    {
        return [
            // magnitude        balanced   transaction amounts...
            [new Amount('30'),  true,      new Amount('10'), new Amount('20'), new Amount('-30')],
            [new Amount('200'), true,      new Amount('200'), new Amount('-200')],
            [new SEK('300'),    true,      new SEK('100'), new SEK('200'), new SEK('-300')],
            [new SEK('200'),    true,      new SEK('200'), new SEK('-200')],
            [new Amount('0'),   false,     new Amount('20'), new Amount('-30')],
            [new Amount('0'),   false,     new Amount('200'), new Amount('-100')],
            [new Amount('0'),   false,     new Amount('10'), new Amount('-10'), new Amount('-10')],
            [new Amount('0'),   false,     new SEK('200'), new SEK('-300')],
            [new Amount('0'),   false,     new SEK('200'), new SEK('-100')],
            [new Amount('0'),   false,     new SEK('100'), new SEK('-100'), new SEK('-100')],
        ];
    }

    /**
     * @dataProvider transactionArithmeticsProvider
     */
    public function testTransactionArithmetics(Amount $magnitude, bool $balanced, Amount ...$amounts)
    {
        $transactions = [];

        foreach ($amounts as $amount) {
            $trans = $this->prophesize(TransactionInterface::CLASS);
            $trans->getAmount()->willReturn($amount);
            $trans->isDeleted()->willReturn(false);
            $transactions[] = $trans->reveal();
        }

        $verification = new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', '', ...$transactions);

        $this->assertSame($balanced, $verification->isBalanced());

        if ($balanced) {
            $this->assertEquals($magnitude, $verification->getMagnitude());
        }
    }

    public function testDeletedTransactionsDoesNotCount()
    {
        $trans = $this->prophesize(TransactionInterface::CLASS);
        $trans->getAmount()->willReturn(new Amount('100'));
        $trans->isDeleted()->willReturn(true);

        $verification = new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', '', $trans->reveal());

        $this->assertSame(0, $verification->getMagnitude()->getInt());
    }

    public function testExceptionOnGetMagnitudeWithUnbalancedVerification()
    {
        $trans = $this->prophesize(TransactionInterface::CLASS);
        $trans->getAmount()->willReturn(new Amount('100'));
        $trans->isDeleted()->willReturn(false);

        $verification = new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, '', '', $trans->reveal());

        $this->expectException(RuntimeException::CLASS);
        $verification->getMagnitude();
    }

    public function testQueryable()
    {
        $trans = $this->createMock(TransactionInterface::CLASS);
        $ver = new Verification(0, new \DateTimeImmutable, new \DateTimeImmutable, 'Verification', '', $trans, $trans);
        $this->assertEquals(new Query([$trans, $trans]), $ver->select());
    }
}
