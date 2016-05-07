<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class AccountTest extends BaseTestCase
{
    public function testExceptionOnToSmallAccountNumber()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);
        new Account\Asset(999, '');
    }

    public function testExceptionOnToLargeAccountNumber()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);
        new Account\Asset(10000, '');
    }

    public function testIsAsset()
    {
        $this->assertTrue(
            (new Account\Asset(1000, ''))->isAsset(),
            'Asset objects should identify themselves using isAsset'
        );
        $this->assertFalse(
            (new Account\Cost(1000, ''))->isAsset(),
            'Non-Asset objects should not identify themselves using isAsset'
        );
    }

    public function testIsCost()
    {
        $this->assertTrue(
            (new Account\Cost(1000, ''))->isCost(),
            'Cost objects should identify themselves using isCost'
        );
        $this->assertFalse(
            (new Account\Debt(1000, ''))->isCost(),
            'Non-Cost objects should not identify themselves using isCost'
        );
    }

    public function testIsDebt()
    {
        $this->assertTrue(
            (new Account\Debt(1000, ''))->isDebt(),
            'Debt objects should identify themselves using isDebt'
        );
        $this->assertFalse(
            (new Account\Earning(1000, ''))->isDebt(),
            'Non-Debt objects should not identify themselves using isDebt'
        );
    }

    public function testIsEarning()
    {
        $this->assertTrue(
            (new Account\Earning(1000, ''))->isEarning(),
            'Earning objects should identify themselves using isEarning'
        );
        $this->assertFalse(
            (new Account\Asset(1000, ''))->isEarning(),
            'Non-Earning objects should not identify themselves using isEarning'
        );
    }

    public function equalProvider()
    {
        return [
            [new Account\Asset(1234, 'foo')],
            [new Account\Debt(1234, 'foo')],
            [new Account\Earning(1234, 'foo')],
            [new Account\Cost(1234, 'foo')],
        ];
    }

    /**
     * @dataProvider equalProvider
     */
    public function testEquals(Account $account)
    {
        $this->assertTrue(
            $account->equals(clone $account),
            'Cloned accounts should be equal'
        );
    }

    public function notEqualProvider()
    {
        return [
            [new Account\Asset(1234, 'foo'), new Account\Asset(1235, 'foo')],
            [new Account\Asset(1234, 'foo'), new Account\Asset(1234, 'bar')],
            [new Account\Asset(1234, 'foo'), new Account\Debt(1234, 'foo')],
            [new Account\Asset(1234, 'foo'), new Account\Earning(1234, 'foo')],
            [new Account\Asset(1234, 'foo'), new Account\Cost(1234, 'foo')],
        ];
    }

    /**
     * @dataProvider notEqualProvider
     */
    public function testNotEquals(Account $left, Account $right)
    {
        $this->assertFalse(
            $left->equals($right),
            'Accounts should not be equal'
        );
    }
}
