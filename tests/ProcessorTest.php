<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class ProcessorTest extends \PHPUnit\Framework\TestCase
{
    public function testCollectTransactions()
    {
        $account = new Account\Cost('1000');
        $dim = new Dimension('2000');

        $container = new Container(
            $transA = new Transaction($account, new Amount('100'), null, $dim),
            $transB = new Transaction($account, new Amount('100'), null, $dim)
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );

        $this->assertEquals(
            [$transA, $transB],
            $dim->getAttribute('transactions')
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            [$transA, $transB],
            $account->getAttribute('transactions')
        );
    }

    public function testProcessAmount()
    {
        $account = new Account\Cost('1000');
        $dimension = new Dimension('2000');

        $container = new Container(
            new Transaction($account, new Amount('100'), null, $dimension),
            new Transaction($account, new Amount('100'), null, $dimension)
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new Amount('200'),
            $account->getAttribute('summary')->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('200'),
            $dimension->getAttribute('summary')->getOutgoingBalance()
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new Amount('200'),
            $account->getAttribute('summary')->getOutgoingBalance()
        );
    }

    public function testProcessQuantity()
    {
        $account = new Account\Cost('1000');
        $dimension = new Dimension('2000');

        $container = new Container(
            new Transaction($account, new Amount('0'), new Amount('1'), $dimension),
            new Transaction($account, new Amount('0'), new Amount('1'), $dimension)
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new Amount('2'),
            $account->getAttribute('quantity_summary')->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('2'),
            $dimension->getAttribute('quantity_summary')->getOutgoingBalance()
        );

        (new Processor)->processContainer($container);

        $this->assertEquals(
            new Amount('2'),
            $account->getAttribute('quantity_summary')->getOutgoingBalance()
        );
    }
}
