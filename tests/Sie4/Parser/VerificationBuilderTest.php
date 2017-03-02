<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Account;
use byrokrat\accounting\Exception;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency;
use Psr\Log\LoggerInterface;

/**
 * @covers \byrokrat\accounting\Sie4\VerificationBuilder
 */
class VerificationBuilderTest extends \PHPUnit\Framework\TestCase
{
    use \byrokrat\accounting\utils\PropheciesTrait;

    public function testCreateTransaction()
    {
        $verificationBuilder = new VerificationBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $transaction = $verificationBuilder->createTransaction(
            $account = $this->prophesizeAccount()->reveal(),
            $dimensions = [$this->prophesizeDimension()->reveal()],
            $amount = $this->createMock(Currency::CLASS),
            $date = new \DateTime,
            $desc = 'desc',
            $quantity = new Amount('10'),
            $sign = 'HF'
        );

        $this->assertSame(
            $account,
            $transaction->getAccount()
        );

        $this->assertSame(
            $dimensions,
            $transaction->getDimensions()
        );

        $this->assertSame(
            $date,
            $transaction->getDate()
        );

        $this->assertSame(
            $desc,
            $transaction->getDescription()
        );

        $this->assertSame(
            $quantity,
            $transaction->getQuantity()
        );

        $this->assertSame(
            $sign,
            $transaction->getSignature()
        );
    }

    public function testCreateVerification()
    {
        $verificationBuilder = new VerificationBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $date = new \DateTime;
        $desc = 'description';

        $transA = $this->prophesizeTransaction(new Currency\SEK('100'));
        $transA->hasDate()->willReturn(false)->shouldBeCalled();
        $transA->setDate($date)->shouldBeCalled();
        $transA->getDescription()->willReturn('')->shouldBeCalled();
        $transA->setDescription($desc)->shouldBeCalled();

        $transB = $this->prophesizeTransaction(new Currency\SEK('-100'));
        $transB->hasDate()->willReturn(false)->shouldBeCalled();
        $transB->setDate($date)->shouldBeCalled();
        $transB->getDescription()->willReturn('')->shouldBeCalled();
        $transB->setDescription($desc)->shouldBeCalled();

        $verification = $verificationBuilder->createVerification(
            $series = 'A',
            $number = '10',
            $date,
            $desc,
            $regdate = new \DateTime,
            $sign = 'HF',
            $transactions = [$transA->reveal(), $transB->reveal()]
        );

        $this->assertSame(
            $series,
            $verification->getAttribute('series')
        );

        $this->assertSame(
            intval($number),
            $verification->getNumber()
        );

        $this->assertSame(
            $date,
            $verification->getDate()
        );

        $this->assertSame(
            $desc,
            $verification->getDescription()
        );

        $this->assertSame(
            $regdate,
            $verification->getRegistrationDate()
        );

        $this->assertSame(
            $sign,
            $verification->getSignature()
        );

        $this->assertSame(
            $transactions,
            $verification->getTransactions()
        );
    }

    public function testErrorOnUnbalancedVerification()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $verificationBuilder = new VerificationBuilder(
            $logger->reveal()
        );

        $transA = $this->prophesizeTransaction(new Currency\SEK('100'));
        $transA->hasDate()->willReturn(true)->shouldBeCalled();
        $transA->getDescription()->willReturn('desc')->shouldBeCalled();

        $verification = $verificationBuilder->createVerification(
            '',
            '',
            new \DateTime,
            '',
            null,
            '',
            [$transA->reveal()]
        );

        $logger->error(\Prophecy\Argument::type('string'))->shouldHaveBeenCalled();
    }
}
