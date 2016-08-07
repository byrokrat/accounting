<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

class VerificationTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait, utils\PropheciesTrait;

    public function testAccessingTransactions()
    {
        $this->assertSame(
            [
                $transactionA = $this->prophesizeTransaction()->reveal(),
                $transactionB = $this->prophesizeTransaction()->reveal()
            ],
            (new Verification)->addTransactions($transactionA, $transactionB)->getTransactions()
        );
    }

    public function testAttributable()
    {
        $this->assertAttributable(new Verification);
    }

    public function testDateable()
    {
        $date = new \DateTimeImmutable();

        $this->assertDateable(
            $date,
            (new Verification)->setDate($date)
        );

        $this->assertTrue(
            (new Verification)->getDate() >= $date
        );
    }

    public function testRegistrationDateable()
    {
        $date = new \DateTimeImmutable();

        $this->assertSame(
            $date,
            (new Verification)->setRegistrationDate($date)->getRegistrationDate()
        );

        $this->assertSame(
            $date,
            (new Verification)->setDate($date)->getRegistrationDate(),
            'If registration date is not set the regular date should be returned'
        );
    }

    public function testDescribable()
    {
        $this->assertDescribable(
            'foobar',
            (new Verification)->setDescription('foobar')
        );
    }

    public function testNumerable()
    {
        $this->assertFalse((new Verification)->hasNumber());

        $this->assertSame(
            10,
            (new Verification)->setNumber(10)->getNumber()
        );

        $this->assertTrue((new Verification)->setNumber(1)->hasNumber());
    }

    public function testQueryable()
    {
        $this->assertCount(
            2,
            (new Verification)->addTransactions(
                $this->prophesizeTransaction()->reveal(),
                $this->prophesizeTransaction()->reveal()
            )->query()->transactions()->toArray()
        );
    }

    public function testSignable()
    {
        $this->assertSignableSignatureNotSet(new Verification);

        $this->assertSignable(
            $signature = 'signature',
            (new Verification)->setSignature($signature)
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
        $verification = new Verification;

        foreach ($amounts as $amount) {
            $verification->addTransactions(
                $this->prophesizeTransaction($amount)->reveal()
            );
        }

        $this->assertSame($balanced, $verification->isBalanced());

        if ($balanced) {
            $this->assertEquals($magnitude, $verification->getMagnitude());
        }
    }

    public function testExceptionOnGetMagnitudeWithUnbalancedVerification()
    {
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        (new Verification)
            ->addTransactions($this->prophesizeTransaction(new Amount('100'))->reveal())
            ->getMagnitude();
    }
}
