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
        $this->assertTrue($account->isCost());
        $this->assertFalse($account->isAsset());
        $this->assertFalse($account->isEarning());
        $this->assertFalse($account->isDebt());
    }

    public function testInferAssetType()
    {
        $this->assertTrue((new Account(id: '1000'))->isAsset());
    }

    public function testInferDebtType()
    {
        $this->assertTrue((new Account(id: '2000'))->isDebt());
    }

    public function testInferEarningType()
    {
        $this->assertTrue((new Account(id: '3000'))->isEarning());
    }

    public function testInferCostType()
    {
        $this->assertTrue((new Account(id: '4000'))->isCost());
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
