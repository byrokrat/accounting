<?php

declare(strict_types=1);

namespace byrokrat\accounting\Processor;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\Account;
use byrokrat\accounting\Dimension\Dimension;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\amount\Amount;

class TransactionProcessorTest extends \PHPUnit\Framework\TestCase
{
    public function testCollectTransactions()
    {
        $account = new Account('1000');
        $dim = new Dimension('2000');

        $transA = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);
        $transB = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);

        $container = new Container($transA, $transB);

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions'),
            'Transactions should be written to account'
        );

        $this->assertEquals(
            [$transA, $transB],
            $dim->getAttribute('transactions'),
            'Transactions should be written to dimension'
        );

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions'),
            'Rerunning processor should yield the same result'
        );
    }

    public function testProcessAmount()
    {
        $account = new Account('1000');
        $dim = new Dimension('2000');

        $transA = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);
        $transB = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);

        $container = new Container($transA, $transB);

        (new TransactionProcessor())->processContainer($container);

        $this->assertTrue(
            $account->getAttribute('summary')->getOutgoingBalance()->equals(new Amount('200'))
        );

        $this->assertTrue(
            $dim->getAttribute('summary')->getOutgoingBalance()->equals(new Amount('200'))
        );

        (new TransactionProcessor())->processContainer($container);

        $this->assertTrue(
            $account->getAttribute('summary')->getOutgoingBalance()->equals(new Amount('200'))
        );
    }
}
