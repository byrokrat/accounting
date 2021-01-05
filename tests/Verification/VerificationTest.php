<?php

declare(strict_types=1);

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidVerificationException;
use byrokrat\accounting\Exception\UnbalancedVerificationException;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;
use Prophecy\Argument;

class VerificationTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Verification();
    }

    public function testAttributesToConstructor()
    {
        $ver = new Verification(attributes: ['key' => 'val']);
        $this->assertSame(
            'val',
            $ver->getAttribute('key')
        );
    }

    public function testExceptionOnNonStringAttributeKey()
    {
        $this->expectException(InvalidArgumentException::class);
        new Verification(attributes: [1 => '']);
    }

    public function testExceptionOnNonStringAttributeValue()
    {
        $this->expectException(InvalidArgumentException::class);
        new Verification(attributes: ['key' => null]);
    }

    public function testExceptionOnNonTransactionArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        new Verification(transactions: ['this-is-not-a-transaction']);
    }

    public function testExceptionOnUnbalancedVerification()
    {
        $this->expectException(UnbalancedVerificationException::class);

        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getAmount()->willReturn(new Amount('1'));
        $trans->isDeleted()->willReturn(false);

        new Verification(transactions: [$trans->reveal()]);
    }

    public function testId()
    {
        $this->assertSame(
            1,
            (new Verification(id: 1))->getVerificationId()
        );
    }

    public function testAssigningDates()
    {
        $transactionDate = new \DateTimeImmutable();
        $registrationDate = new \DateTimeImmutable();

        $ver = new Verification(
            transactionDate: $transactionDate,
            registrationDate: $registrationDate,
        );

        $this->assertSame($transactionDate, $ver->getTransactionDate());
        $this->assertSame($registrationDate, $ver->getRegistrationDate());
    }

    public function testTransactionDefaultDate()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            (new Verification())->getTransactionDate()
        );
    }

    public function testRegistrationDateDefaultsToTransactionDate()
    {
        $transactionDate = new \DateTimeImmutable();

        $this->assertSame(
            $transactionDate,
            (new Verification(transactionDate: $transactionDate))->getRegistrationDate()
        );
    }

    public function testDescription()
    {
        $this->assertSame(
            'desc',
            (new Verification(description: 'desc'))->getDescription()
        );
    }

    public function testSignature()
    {
        $this->assertSame(
            'sign',
            (new Verification(signature: 'sign'))->getSignature()
        );
    }

    public function testAccessingTransactions()
    {
        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getAmount()->willReturn(new Amount('0'));
        $trans->isDeleted()->willReturn(false);
        $trans = $trans->reveal();

        $this->assertSame(
            [$trans, $trans],
            (new Verification(transactions: [$trans, $trans]))->getTransactions()
        );
    }

    public function transactionArithmeticsProvider()
    {
        return [
            // magnitude        transaction amounts...
            [new Amount('30'),  new Amount('10'),  new Amount('20'), new Amount('-30')],
            [new Amount('200'), new Amount('200'), new Amount('-200')],
            [new SEK('300'),    new SEK('100'),    new SEK('200'),   new SEK('-300')],
            [new SEK('200'),    new SEK('200'),    new SEK('-200')],
        ];
    }

    /**
     * @dataProvider transactionArithmeticsProvider
     */
    public function testTransactionArithmetics(Amount $magnitude, Amount ...$amounts)
    {
        $transactions = [];

        foreach ($amounts as $amount) {
            $trans = $this->prophesize(TransactionInterface::class);
            $trans->getAmount()->willReturn($amount);
            $trans->isDeleted()->willReturn(false);
            $transactions[] = $trans->reveal();
        }

        $verification = new Verification(transactions: $transactions);

        $this->assertTrue($magnitude->equals($verification->getMagnitude()));
    }

    public function testExceptionOnCurrencyMissmatch()
    {
        $transA = $this->prophesize(TransactionInterface::class);
        $transA->getAmount()->willReturn(new Amount('0'));
        $transA->isDeleted()->willReturn(false);

        $transSEK = $this->prophesize(TransactionInterface::class);
        $transSEK->getAmount()->willReturn(new SEK('0'));
        $transSEK->isDeleted()->willReturn(false);

        $this->expectException(InvalidVerificationException::class);
        $verification = new Verification(transactions: [$transSEK->reveal(), $transA->reveal()]);
    }

    public function testExceptionOnCurrencyMissmatchInDeletedTransactions()
    {
        $transA = $this->prophesize(TransactionInterface::class);
        $transA->getAmount()->willReturn(new Amount('0'));
        $transA->isDeleted()->willReturn(true);

        $transSEK = $this->prophesize(TransactionInterface::class);
        $transSEK->getAmount()->willReturn(new SEK('0'));
        $transSEK->isDeleted()->willReturn(true);

        $this->expectException(InvalidVerificationException::class);
        $verification = new Verification(transactions: [$transSEK->reveal(), $transA->reveal()]);
    }

    public function testDeletedTransactionsDoesNotCount()
    {
        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getAmount()->willReturn(new Amount('100'));
        $trans->isDeleted()->willReturn(true);

        $verification = new Verification(transactions: [$trans->reveal()]);

        $this->assertSame(0, $verification->getMagnitude()->getInt());
    }

    public function testQueryable()
    {
        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getAmount()->willReturn(new Amount('100'));
        $trans->isDeleted()->willReturn(true);
        $trans = $trans->reveal();

        $this->assertEquals(
            new Query([$trans, $trans]),
            (new Verification(transactions: [$trans, $trans]))->select()
        );
    }
}
