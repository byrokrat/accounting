<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Amount;
use Psr\Log\LoggerInterface;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Sie4\VerificationBuilder
 */
class VerificationBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateVerification()
    {
        $verificationBuilder = new VerificationBuilder(
            $this->createMock(LoggerInterface::CLASS)
        );

        $date = new \DateTimeImmutable;
        $desc = 'description';

        $transA = $this->prophesize(TransactionInterface::CLASS);
        $transA->getAmount()->willReturn(new Amount('100'));
        $transA->isDeleted()->willReturn(false);

        $transB = $this->prophesize(TransactionInterface::CLASS);
        $transB->getAmount()->willReturn(new Amount('-100'));
        $transB->isDeleted()->willReturn(false);

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
            $verification->getTransactionDate()
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

        $transA = $this->prophesize(TransactionInterface::CLASS);
        $transA->getAmount()->willReturn(new Amount('100'));
        $transA->isDeleted()->willReturn(false);

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
