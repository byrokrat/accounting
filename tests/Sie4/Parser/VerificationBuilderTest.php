<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Account;
use byrokrat\accounting\Exception;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency;
use Psr\Log\LoggerInterface;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Sie4\VerificationBuilder
 */
class VerificationBuilderTest extends \PHPUnit\Framework\TestCase
{
    use \byrokrat\accounting\utils\PropheciesTrait;

    public function testCreateVerification()
    {
        $verificationBuilder = new VerificationBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $date = new \DateTimeImmutable;
        $desc = 'description';

        $transA = $this->prophesizeTransaction(new Currency\SEK('100'));
        $transA->hasDate()->willReturn(false)->shouldBeCalled();
        $transA->setDate($date)->shouldBeCalled();
        $transA->getDescription()->willReturn('')->shouldBeCalled();
        $transA->setDescription($desc)->shouldBeCalled();
        $transA->setAttribute('ver_num', Argument::any())->shouldBeCalled();

        $transB = $this->prophesizeTransaction(new Currency\SEK('-100'));
        $transB->hasDate()->willReturn(false)->shouldBeCalled();
        $transB->setDate($date)->shouldBeCalled();
        $transB->getDescription()->willReturn('')->shouldBeCalled();
        $transB->setDescription($desc)->shouldBeCalled();
        $transB->setAttribute('ver_num', Argument::any())->shouldBeCalled();

        $verification = $verificationBuilder->createVerification(
            $series = 'A',
            $number = '10',
            $date,
            $desc,
            $regdate = new \DateTimeImmutable,
            $sign = 'HF',
            $transactions = [$transA->reveal(), $transB->reveal()]
        );

        $this->assertSame(
            $series,
            $verification->getAttribute('series')
        );

        $this->assertSame(
            intval($number),
            $verification->getId()
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
        $transA->setAttribute('ver_num', Argument::any())->shouldBeCalled();

        $verification = $verificationBuilder->createVerification(
            '',
            '',
            new \DateTimeImmutable,
            '',
            null,
            '',
            [$transA->reveal()]
        );

        $logger->error(\Prophecy\Argument::type('string'))->shouldHaveBeenCalled();
    }
}
