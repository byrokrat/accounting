<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class SummaryTest extends \PHPUnit\Framework\TestCase
{
    use utils\PropheciesTrait;

    public function testBasicSummary()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());

        $this->assertEquals(
            new Amount('10'),
            $summary->getIncomingBalance()
        );

        $this->assertEquals(
            new Amount('10'),
            $summary->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getDebit()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getCredit()
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertEquals(
            new Amount('5'),
            $summary->getMagnitude()
        );
    }

    public function testPositiveBalance()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());

        $this->assertEquals(
            new Amount('10'),
            $summary->getIncomingBalance()
        );

        $this->assertEquals(
            new Amount('15'),
            $summary->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('10'),
            $summary->getDebit()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getCredit()
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeBalance()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());

        $this->assertEquals(
            new Amount('10'),
            $summary->getIncomingBalance()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getDebit()
        );

        $this->assertEquals(
            new Amount('10'),
            $summary->getCredit()
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeIncomingBalance()
    {
        $summary = new Summary(new Amount('-10'));

        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());

        $this->assertEquals(
            new Amount('-10'),
            $summary->getIncomingBalance()
        );

        $this->assertEquals(
            new Amount('-10'),
            $summary->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getDebit()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getCredit()
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertEquals(
            new Amount('5'),
            $summary->getMagnitude()
        );
    }

    public function testExceptionOnGetMagnitudeWithoutBalance()
    {
        $this->expectException(Exception\RuntimeException::CLASS);

        (new Summary)
            ->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal())
            ->getMagnitude();
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new Summary;

        $summary->addTransaction($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addTransaction($this->prophesizeTransaction(new Amount('-5'))->reveal());

        $this->assertEquals(
            new Amount('0'),
            $summary->getIncomingBalance()
        );

        $this->assertEquals(
            new Amount('0'),
            $summary->getOutgoingBalance()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getDebit()
        );

        $this->assertEquals(
            new Amount('5'),
            $summary->getCredit()
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertEquals(
            new Amount('5'),
            $summary->getMagnitude()
        );
    }

    public function testExceptionOnUninitializedSummaries()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        (new Summary)->getMagnitude();
    }

    public function testGetTransactions()
    {
        $summary = new Summary;

        $transA = $this->prophesizeTransaction(new Amount('5'))->reveal();
        $transB = $this->prophesizeTransaction(new Amount('-5'))->reveal();

        $summary->addTransaction($transA);
        $summary->addTransaction($transB);

        $this->assertEquals(
            [$transA, $transB],
            $summary->getTransactions()
        );
    }
}
