<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

/**
 * @covers \byrokrat\accounting\Verification
 */
class VerificationTest extends BaseTestCase
{
    public function testGetText()
    {
        $this->assertEquals(
            (new Verification('foobar'))->getText(),
            'foobar'
        );
    }

    public function testGetDate()
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
        $transactionA = $this->getTransactionMock();
        $transactionB = $this->getTransactionMock();

        $verification = new Verification('', null, $transactionA, $transactionB);

        $this->assertSame(
            [$transactionA, $transactionB],
            $verification->getTransactions()
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
            $verification->addTransaction($this->getTransactionMock($amount));
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
            ->addTransaction($this->getTransactionMock(new Amount('100')))
            ->getMagnitude();
    }
}
