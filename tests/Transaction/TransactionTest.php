<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidTransactionException;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );
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

    public function testExceptionOnNonStringAttributeKey()
    {
        $this->expectException(InvalidArgumentException::class);

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            attributes: [1 => 'val'],
        );
    }

    public function testExceptionOnNonStringAttributeValue()
    {
        $this->expectException(InvalidArgumentException::class);

        new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            attributes: ['key' => null],
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

    public function testGetVerificationId()
    {
        $trans = new Transaction(
            verificationId: 999,
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame(999, $trans->getVerificationId());
    }

    public function testGetTransactionDate()
    {
        $transactionDate = new \DateTimeImmutable();

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

        $this->assertInstanceOf(\DateTimeImmutable::class, $trans->getTransactionDate());
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

    public function testGetQuantity()
    {
        $quantity = new Amount('100');

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $this->createMock(AccountInterface::class),
            quantity: $quantity,
        );

        $this->assertSame($quantity, $trans->getQuantity());
    }

    public function testDefaultQuantity()
    {
        $trans = new Transaction(
            amount: new Amount('100'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertEquals(new Amount('0'), $trans->getQuantity());
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

    public function testQueryable()
    {
        $account = $this->prophesize(AccountInterface::class);
        $account->select()->willReturn(new Query());
        $account = $account->reveal();

        $trans = new Transaction(
            account: $account,
            amount: new Amount('0'),
            dimensions: [$account],
        );

        $this->assertEquals(
            [$account, $account],
            $trans->select()->asArray()
        );
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
}
