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

        $trans = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);

        $container = new Container($trans, $trans);

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$trans, $trans],
            $account->getAttribute('transactions')
        );

        $this->assertEquals(
            [$trans, $trans],
            $dim->getAttribute('transactions')
        );

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$trans, $trans],
            $account->getAttribute('transactions')
        );
    }

    public function testProcessAmount()
    {
        $account = new Account('1000');
        $dim = new Dimension('2000');

        $trans = new Transaction(amount: new Amount('100'), account: $account, dimensions: [$dim]);

        $container = new Container($trans, $trans);

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

    public function testProcessQuantity()
    {
        $account = new Account('1000');
        $dim = new Dimension('2000');

        $trans = new Transaction(
            amount: new Amount('0'),
            account: $account,
            quantity: new Amount('1'),
            dimensions: [$dim],
        );

        $container = new Container($trans, $trans);

        (new TransactionProcessor())->processContainer($container);

        $this->assertTrue(
            $account->getAttribute('quantity_summary')->getOutgoingBalance()->equals(new Amount('2'))
        );

        $this->assertTrue(
            $dim->getAttribute('quantity_summary')->getOutgoingBalance()->equals(new Amount('2'))
        );

        (new TransactionProcessor())->processContainer($container);

        $this->assertTrue(
            $account->getAttribute('quantity_summary')->getOutgoingBalance()->equals(new Amount('2'))
        );
    }
}
