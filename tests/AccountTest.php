<?php

namespace byrokrat\accounting;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    public function invalidNumberProvider()
    {
        return [
            [123],
            [12345],
        ];
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testInvalidAccountNumber($number)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);
        new Account\Asset($number, '');
    }

    public function equalProvider()
    {
        return [
            [new Account\Asset(1234, 'foo'), new Account\Asset(1234, 'foo')],
            [new Account\Debt(1234, 'foo'), new Account\Debt(1234, 'foo')],
            [new Account\Earning(1234, 'foo'), new Account\Earning(1234, 'foo')],
            [new Account\Cost(1234, 'foo'), new Account\Cost(1234, 'foo')],
        ];
    }

    /**
     * @dataProvider equalProvider
     */
    public function testEquals(Account $left, Account $right)
    {
        $this->assertTrue(
            $left->equals($right),
            'Accounts should be equal'
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
