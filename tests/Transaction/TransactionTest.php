<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidTransactionException;
use byrokrat\accounting\Summary;
use byrokrat\amount\Amount;
use Prophecy\Argument;

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testAttributes()
    {
        $attributable = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertFalse($attributable->hasAttribute('does-not-exist'));

        $this->assertSame('', $attributable->getAttribute('does-not-exist'));

        $attributable->setAttribute('foo', 'bar');

        $this->assertTrue($attributable->hasAttribute('foo'));

        $this->assertSame('bar', $attributable->getAttribute('foo'));

        $this->assertSame(['foo' => 'bar'], $attributable->getAttributes());
    }

    public function testId()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertIsString($trans->getId());
    }

    public function testExceptionOnNonDimension()
    {
        $this->expectException(InvalidArgumentException::class);

        new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            dimensions: ['this-is-not-a-dimension-object'],
        );
    }

    public function testAttributesToConstructor()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            attributes: ['key' => 'val'],
        );

        $this->assertSame(
            'val',
            $trans->getAttribute('key')
        );
    }

    public function testGetAmount()
    {
        $amount = new Amount('100');

        $trans = new Transaction(
            amount: $amount,
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame($amount, $trans->getAmount());
    }

    public function testGetAccount()
    {
        $account = $this->createMock(AccountInterface::class);

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $account,
        );

        $this->assertSame($account, $trans->getAccount());
    }

    public function testAddTransactionCalledOnAccount()
    {
        $account = $this->prophesize(AccountInterface::class);

        $account->addTransaction(Argument::type(Transaction::class))->shouldBeCalled();

        new Transaction(
            amount: new Amount('0'),
            account: $account->reveal(),
        );
    }

    public function testGetVerificationId()
    {
        $trans = new Transaction(
            verificationId: '999',
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame('999', $trans->getVerificationId());
    }

    public function testGetTransactionDate()
    {
        $transactionDate = AccountingDate::fromString('20210112');

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            transactionDate: $transactionDate,
        );

        $this->assertSame($transactionDate, $trans->getTransactionDate());
    }

    public function testDefaultTransactionDate()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame(AccountingDate::today(), $trans->getTransactionDate());
    }

    public function testGetDescription()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            description: 'desc',
        );

        $this->assertSame('desc', $trans->getDescription());
    }

    public function testGetSignature()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            signature: 'sign',
        );

        $this->assertSame('sign', $trans->getSignature());
    }

    public function testGetDimensions()
    {
        $dim = $this->createMock(DimensionInterface::class);

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            dimensions: [$dim, $dim],
        );

        $this->assertSame([$dim, $dim], $trans->getDimensions());
    }

    public function testAddTransactionCalledOnDimension()
    {
        $dim = $this->prophesize(DimensionInterface::class);

        $dim->addTransaction(Argument::type(Transaction::class))->shouldBeCalled();

        new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            dimensions: [$dim->reveal()],
        );
    }

    public function testGetItems()
    {
        $account = $this->createMock(AccountInterface::class);
        $dim = $this->createMock(DimensionInterface::class);

        $trans = new Transaction(
            account: $account,
            amount: new Amount('0'),
            dimensions: [$dim, $dim],
        );

        $this->assertEquals([$account, $dim, $dim], $trans->getItems());
    }

    public function testDefaultsToNotAdded()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertFalse($trans->isAdded());
    }

    public function testAdded()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            added: true,
        );

        $this->assertTrue($trans->isAdded());
    }

    public function testDefaultsToNotDeleted()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertFalse($trans->isDeleted());
    }

    public function testDeleted()
    {
        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            deleted: true,
        );

        $this->assertTrue($trans->isDeleted());
    }

    public function testExceptionOnAddedAndDeleted()
    {
        $this->expectException(InvalidTransactionException::class);

        new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            added: true,
            deleted: true,
        );
    }

    public function testGetSummary()
    {
        $trans = new Transaction(
            amount: new Amount('100'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertEquals(
            Summary::fromAmount(new Amount('100')),
            $trans->getSummary()
        );
    }

    public function testGetZeroSummaryForDeletedTransaction()
    {
        $trans = new Transaction(
            amount: new Amount('100'),
            account: $this->createMock(AccountInterface::class),
            deleted: true
        );

        $this->assertTrue(
            $trans->getSummary()->getMagnitude()->equals(new Amount('0'))
        );
    }
}
