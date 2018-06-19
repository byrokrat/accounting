<?php

declare(strict_types = 1);

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

        $date = new \DateTimeImmutable;

        $transA = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);

        $container = new Container($transA, $transB);

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );

        $this->assertEquals(
            [$transA, $transB],
            $dim->getAttribute('transactions')
        );

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );
    }

    public function testProcessAmount()
    {
        $account = new CostAccount('1000');
        $dim = new Dimension('2000');

        $date = new \DateTimeImmutable;

        $transA = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('100'), new Amount('0'), $account, $dim);

        $container = new Container($transA, $transB);

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            new Amount('200'),
            $account->getAttribute('summary')->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('200'),
            $dim->getAttribute('summary')->getOutgoingBalance()
        );

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            new Amount('200'),
            $account->getAttribute('summary')->getOutgoingBalance()
        );
    }

    public function testProcessQuantity()
    {
        $account = new CostAccount('1000');
        $dim = new Dimension('2000');

        $date = new \DateTimeImmutable;

        $transA = new Transaction(0, $date, '', '', new Amount('0'), new Amount('1'), $account, $dim);
        $transB = new Transaction(0, $date, '', '', new Amount('0'), new Amount('1'), $account, $dim);

        $container = new Container($transA, $transB);

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            new Amount('2'),
            $account->getAttribute('quantity_summary')->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('2'),
            $dim->getAttribute('quantity_summary')->getOutgoingBalance()
        );

        (new TransactionProcessor)->processContainer($container);

        $this->assertEquals(
            new Amount('2'),
            $account->getAttribute('quantity_summary')->getOutgoingBalance()
        );
    }
}
