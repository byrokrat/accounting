<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class TransactionSummaryTest extends \PHPUnit_Framework_TestCase
{
    use utils\PropheciesTrait;

    public function testBasicSummary()
    {
        $summary = new TransactionSummary(new Amount('10'));

        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());

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
        $summary = new TransactionSummary(new Amount('10'));

        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());

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
        $summary = new TransactionSummary(new Amount('10'));

        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());

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
        $summary = new TransactionSummary(new Amount('-10'));

        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());

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
        $this->setExpectedException(Exception\RuntimeException::CLASS);

        (new TransactionSummary)
            ->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal())
            ->getMagnitude();
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new TransactionSummary;

        $summary->addToSummary($this->prophesizeTransaction(new Amount('5'))->reveal());
        $summary->addToSummary($this->prophesizeTransaction(new Amount('-5'))->reveal());

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
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        (new TransactionSummary)->getMagnitude();
    }

    public function testGetTransactions()
    {
        $summary = new TransactionSummary;

        $transA = $this->prophesizeTransaction(new Amount('5'))->reveal();
        $transB = $this->prophesizeTransaction(new Amount('-5'))->reveal();

        $summary->addToSummary($transA);
        $summary->addToSummary($transB);

        $this->assertEquals(
            [$transA, $transB],
            $summary->getTransactions()
        );
    }
}
