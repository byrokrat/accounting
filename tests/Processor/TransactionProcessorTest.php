<?php

declare(strict_types=1);

namespace byrokrat\accounting\Processor;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\CostAccount;
use byrokrat\accounting\Dimension\Dimension;
use byrokrat\accounting\Transaction\Transaction;
use byrokrat\amount\Amount;

class TransactionProcessorTest extends \PHPUnit\Framework\TestCase
{
    public function testCollectTransactions()
    {
        $account = new CostAccount('1000');
        $dim = new Dimension('2000');

        $date = new \DateTimeImmutable();

        $transA = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);

        $container = new Container($transA, $transB);

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );

        $this->assertEquals(
            [$transA, $transB],
            $dim->getAttribute('transactions')
        );

        (new TransactionProcessor())->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );
    }

    public function testProcessAmount()
    {
        $account = new CostAccount('1000');
        $dim = new Dimension('2000');

        $date = new \DateTimeImmutable();

        $transA = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);

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

    public function testProcessQuantity()
    {
        $account = new CostAccount('1000');
        $dim = new Dimension('2000');

        $date = new \DateTimeImmutable();

        $transA = new Transaction(0, $date, '', '', new Amount('0'), new Amount('1'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('0'), new Amount('1'), $account, $dim);

        $container = new Container($transA, $transB);

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
