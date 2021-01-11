<?php

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\LogicException;

class AccountTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionOnNonNumericAccountId()
    {
        $this->expectException(InvalidAccountException::class);
        new Account(id: 'this-is-not-a-numerical-string');
    }

    public function testExceptionOnInvalidType()
    {
        $this->expectException(InvalidAccountException::class);
        new Account(id: '1234', type: 'this-tyoe-id-does-not-exist');
    }

    public function testId()
    {
        $this->assertSame(
            '1234',
            (new Account(id: '1234'))->getId()
        );
    }

    public function testDescription()
    {
        $this->assertSame(
            'desc',
            (new Account(id: '0', description: 'desc'))->getDescription()
        );
    }

    public function testType()
    {
        $account = new Account(id: '0', type: AccountInterface::TYPE_COST);
        $this->assertSame(AccountInterface::TYPE_COST, $account->getType());
        $this->assertTrue($account->isCostAccount());
        $this->assertFalse($account->isAssetAccount());
        $this->assertFalse($account->isEarningAccount());
        $this->assertFalse($account->isDebtAccount());
    }

    public function testInferAssetType()
    {
        $account = new Account(id: '1000');

        $this->assertTrue($account->isAssetAccount());
        $this->assertTrue($account->isBalanceAccount());
        $this->assertFalse($account->isResultAccount());
    }

    public function testInferDebtType()
    {
        $account = new Account(id: '2000');

        $this->assertTrue($account->isDebtAccount());
        $this->assertTrue($account->isBalanceAccount());
        $this->assertFalse($account->isResultAccount());
    }

    public function testInferEarningType()
    {
        $account = new Account(id: '3000');

        $this->assertTrue($account->isEarningAccount());
        $this->assertFalse($account->isBalanceAccount());
        $this->assertTrue($account->isResultAccount());
    }

    public function testInferCostType()
    {
        $account = new Account(id: '4000');

        $this->assertTrue($account->isCostAccount());
        $this->assertFalse($account->isBalanceAccount());
        $this->assertTrue($account->isResultAccount());
    }

    public function testAttribute()
    {
        $this->assertSame(
            'bar',
            (new Account(id: '0', attributes: ['foo' => 'bar']))->getAttribute('foo')
        );
    }

    public function testExceptionOnAddChild()
    {
        $this->expectException(LogicException::class);
        (new Account(id: '1'))->addChild(new Account(id: '2'));
    }
}
