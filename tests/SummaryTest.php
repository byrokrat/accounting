<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class SummaryTest extends \PHPUnit\Framework\TestCase
{
    public function testBasicSummary()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getDebit()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCredit()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testPositiveBalance()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('15'))
        );

        $this->assertTrue(
            $summary->getDebit()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getCredit()->equals(new Amount('5'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeBalance()
    {
        $summary = new Summary(new Amount('10'));

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));
        $summary->addAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getDebit()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCredit()->equals(new Amount('10'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeIncomingBalance()
    {
        $summary = new Summary(new Amount('-10'));

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('-10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('-10'))
        );

        $this->assertTrue(
            $summary->getDebit()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCredit()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testExceptionOnGetMagnitudeWithoutBalance()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        $summary = new Summary();
        $summary->addAmount(new Amount('5'));
        $summary->getMagnitude();
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new Summary();

        $summary->addAmount(new Amount('5'));
        $summary->addAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('0'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('0'))
        );

        $this->assertTrue(
            $summary->getDebit()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCredit()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testExceptionOnUninitializedSummaries()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        (new Summary())->getMagnitude();
    }
}
