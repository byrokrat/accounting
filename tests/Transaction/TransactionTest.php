<?php

declare(strict_types=1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\AttributableTestTrait;
use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Exception\LogicException;
use byrokrat\accounting\Query;
use byrokrat\amount\Amount;

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );
    }

    public function testExceptionOnNonDimension()
    {
        $this->expectException(LogicException::class);
        new Transaction(
            1,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            ['this-is-not-a-dimension-object']
        );
    }

    public function testAttributesToConstructor()
    {
        $trans = new Transaction(
            1,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            [],
            ['key' => 'val']
        );

        $this->assertSame(
            'val',
            $trans->getAttribute('key')
        );
    }

    public function testExceptionOnNonStringAttributeKey()
    {
        $this->expectException(LogicException::class);
        $trans = new Transaction(
            1,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            [],
            [1 => 'val']
        );
    }

    public function testExceptionOnNonStringAttributeValue()
    {
        $this->expectException(LogicException::class);
        new Transaction(
            1,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            [],
            ['key' => null]
        );
    }

    public function testGetVerificationId()
    {
        $trans = new Transaction(
            999,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame(999, $trans->getVerificationId());
    }

    public function testGetTransactionDate()
    {
        $trans = new Transaction(
            999,
            $date = new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame($date, $trans->getTransactionDate());
    }

    public function testGetDescription()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            'desc',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame('desc', $trans->getDescription());
    }

    public function testGetSignature()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            'sign',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame('sign', $trans->getSignature());
    }

    public function testGetAmount()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            $amount = new Amount('100'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame($amount, $trans->getAmount());
    }

    public function testGetQuantity()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            $quantity = new Amount('100'),
            $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame($quantity, $trans->getQuantity());
    }

    public function testGetAccount()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $account = $this->createMock(AccountInterface::class),
            []
        );

        $this->assertSame($account, $trans->getAccount());
    }

    public function testGetDimensions()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            new Amount('0'),
            new Amount('0'),
            $this->createMock(AccountInterface::class),
            [
                $dimA = $this->createMock(DimensionInterface::class),
                $dimB = $this->createMock(DimensionInterface::class),
            ]
        );

        $this->assertSame([$dimA, $dimB], $trans->getDimensions());
    }

    public function testQueryable()
    {
        $trans = new Transaction(
            0,
            new \DateTimeImmutable(),
            '',
            '',
            $amount = new Amount('0'),
            new Amount('0'),
            $account = $this->createMock(AccountInterface::class),
            [
                $dimA = $this->createMock(DimensionInterface::class),
                $dimB = $this->createMock(DimensionInterface::class),
            ]
        );

        $this->assertEquals(
            new Query([$account, $amount, $dimA, $dimB]),
            $trans->select()
        );
    }

    public function testIsAdded()
    {
        $this->assertFalse($this->getAttributableToTest()->isAdded());
    }

    public function testIsDeleted()
    {
        $this->assertFalse($this->getAttributableToTest()->isDeleted());
    }
}
