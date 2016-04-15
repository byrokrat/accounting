<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

class TransactionSetTest extends BaseTestCase
{
    public function testIteration()
    {
        $transactions = [
            0 => $this->getTransactionMock(),
            1 => $this->getTransactionMock(),
        ];

        $this->assertEquals(
            $transactions,
            iterator_to_array(new TransactionSet(...$transactions))
        );
    }

    public function testGetAccounts()
    {
        $a1920 = $this->prophesize(Account::CLASS);
        $a1920->getNumber()->willReturn(1920);

        $a3000 = $this->prophesize(Account::CLASS);
        $a3000->getNumber()->willReturn(3000);

        $set = new TransactionSet(
            $this->getTransactionMock(null, $a1920->reveal()),
            $this->getTransactionMock(null, $a1920->reveal()),
            $this->getTransactionMock(null, $a3000->reveal())
        );

        $accounts = iterator_to_array($set->getAccounts());

        $this->assertCount(
            2,
            $accounts,
            'Set contains 2 unique accounts and this should be relflected in count'
        );

        $this->assertArrayHasKey(1920, $accounts);
        $this->assertArrayHasKey(3000, $accounts);
    }

    public function testSum()
    {
        $this->assertEquals(
            new Amount('0'),
            (
                new TransactionSet(
                    $this->getTransactionMock(new Amount('100')),
                    $this->getTransactionMock(new Amount('200')),
                    $this->getTransactionMock(new Amount('-300'))
                )
            )->getSum()
        );

        $this->assertEquals(
            new Amount('-100'),
            (
                new TransactionSet(
                    $this->getTransactionMock(new Amount('200')),
                    $this->getTransactionMock(new Amount('-300'))
                )
            )->getSum()
        );

        $this->assertEquals(
            new Amount('100'),
            (
                new TransactionSet(
                    $this->getTransactionMock(new Amount('200')),
                    $this->getTransactionMock(new Amount('-100'))
                )
            )->getSum()
        );
    }

    public function testCurrency()
    {
        $this->assertEquals(
            new SEK('100'),
            (
                new TransactionSet(
                    $this->getTransactionMock(new SEK('200')),
                    $this->getTransactionMock(new SEK('-100'))
                )
            )->getSum()
        );
    }
}
