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

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

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

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

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

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));
        $summary->addAmount(new Amount('-5'));

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

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

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
            ->addAmount(new Amount('5'))
            ->getMagnitude();
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new Summary;

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

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
}
