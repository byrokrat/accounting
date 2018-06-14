<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Transaction;

use byrokrat\accounting\utils;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Exception\LogicException;
use byrokrat\amount\Amount;

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    use utils\PropheciesTrait,
        utils\AttributableTestsTrait,
        utils\DescriptionTestsTrait,
        utils\SignatureTestsTrait;

    protected function getObjectToTest()
    {
        return $this->createTransaction();
    }

    private function createTransaction(&$account = null, &$amount = null, &$quantity = null, &$dimensions = null)
    {
        return new Transaction(
            $account = $this->prophesizeAccount()->reveal(),
            $amount = $this->prophesizeAmount()->reveal(),
            $quantity = $this->prophesizeAmount()->reveal(),
            ...$dimensions = [
                $this->prophesizeDimension()->reveal(),
                $this->prophesizeDimension()->reveal()
            ]
        );
    }

    public function testAccessingContent()
    {
        $transaction = $this->createTransaction($account, $amount, $quantity, $dimensions);

        $this->assertSame($account, $transaction->getAccount());
        $this->assertSame($amount, $transaction->getAmount());
        $this->assertSame($quantity, $transaction->getQuantity());
        $this->assertSame($dimensions, $transaction->getDimensions());
    }

    public function testDate()
    {
        $trans = $this->createTransaction();
        $this->assertFalse($trans->hasDate());
        $date = new \DateTimeImmutable;
        $trans->setDate($date);
        $this->assertTrue($trans->hasDate());
        $this->assertSame($date, $trans->getDate());
    }

    public function testExceptionWhenDateNotSet()
    {
        $this->expectException(LogicException::CLASS);
        $this->createTransaction()->getDate();
    }

    public function testQueryable()
    {
        $transaction = $this->createTransaction($account, $amount, $void, $dimensions);

        $this->assertSame(
            array_merge([$account, $amount], $dimensions),
            $transaction->select()->asArray()
        );
    }

    public function testCastToString()
    {
        $account = $this->prophesize(AccountInterface::CLASS);
        $account->__toString()->willReturn('account');

        $amount = $this->prophesize(Amount::CLASS);
        $amount->getString()->willReturn('amount');

        $transaction = new Transaction(
            $account->reveal(),
            $amount->reveal()
        );

        $this->assertSame(
            'account: amount',
            (string)$transaction
        );
    }

    public function testIsAdded()
    {
        $this->assertFalse(
            $this->createTransaction()->isAdded()
        );
    }

    public function testIsDeleted()
    {
        $this->assertFalse(
            $this->createTransaction()->isDeleted()
        );
    }
}
