<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Transaction;
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

        $transactionData = [
            [
                'type' => Transaction\Transaction::CLASS,
                'account' => $this->createMock(AccountInterface::CLASS),
                'dimensions' => [],
                'amount' => new Amount('100'),
                'date' => null,
                'description' => null,
                'quantity' => new Amount('0'),
                'signature' => null,
            ],
            [
                'type' => Transaction\Transaction::CLASS,
                'account' => $this->createMock(AccountInterface::CLASS),
                'dimensions' => [],
                'amount' => new Amount('-100'),
                'date' => null,
                'description' => null,
                'quantity' => new Amount('0'),
                'signature' => null,
            ],
        ];

        $verification = $verificationBuilder->createVerification(
            $series = 'A',
            $number = '10',
            $date,
            $desc,
            $regdate = new \DateTimeImmutable,
            $sign = 'HF',
            $transactionData
            #$transactions = [$transA->reveal(), $transB->reveal()]
        );

        $this->assertSame(
            $series,
            $verification->getAttribute('series')
        );

        $this->assertSame(
            intval($number),
            $verification->getVerificationId()
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
    }

    public function testErrorOnUnbalancedVerification()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $verificationBuilder = new VerificationBuilder(
            $logger->reveal()
        );

        $transactionData = [
            [
                'type' => Transaction\Transaction::CLASS,
                'account' => $this->createMock(AccountInterface::CLASS),
                'dimensions' => [],
                'amount' => new Amount('100'),
                'date' => null,
                'description' => null,
                'quantity' => new Amount('0'),
                'signature' => null,
            ],
        ];

        $verification = $verificationBuilder->createVerification(
            '',
            '',
            new \DateTimeImmutable,
            '',
            null,
            '',
            $transactionData
        );

        $logger->error(\Prophecy\Argument::type('string'))->shouldHaveBeenCalled();
    }
}
