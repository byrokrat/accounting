<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\SummaryEmptyException;
use byrokrat\accounting\Exception\SummaryNotBalancedException;
use byrokrat\amount\Amount;

class SummaryTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptySummaryIsEmpty()
    {
        $this->assertTrue((new Summary())->isEmpty());
    }

    public function testNotEmptyWhenIncomingBalanceIsSet()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $this->assertFalse($summary->isEmpty());
    }

    public function testNotEmptyWhenAmountIsSet()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('1'));

        $this->assertFalse($summary->isEmpty());
    }

    public function testExceptionGetIncomingBalanceOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getIncomingBalance();
    }

    public function testGetIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('1')));
    }

    public function testGetEmptyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('0')));
    }

    public function testExceptionGetOutgoingBalanceOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getOutgoingBalance();
    }

    public function testGetOutgoingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $summary = $summary->withAmount(new Amount('1'));

        $this->assertTrue($summary->getOutgoingBalance()->equals(new Amount('2')));
    }

    public function testExceptionGetDebitOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getDebitTotal();
    }

    public function testExceptionGetCreditOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getCreditTotal();
    }

    public function testGetDebitAndCreditOnPositiveAmount()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('1'));

        $this->assertTrue($summary->getDebitTotal()->equals(new Amount('1')));
        $this->assertTrue($summary->getCreditTotal()->equals(new Amount('0')));
    }

    public function testGetDebitAndCreditOnNegativeAmount()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('-1'));

        $this->assertTrue($summary->getDebitTotal()->equals(new Amount('0')));
        $this->assertTrue($summary->getCreditTotal()->equals(new Amount('1')));
    }

    public function testEmptySummaryIsConsideredBalanced()
    {
        $this->assertTrue((new Summary())->isBalanced());
    }

    public function testNotBalancedSummary()
    {
        $summary = (new Summary());

        $summary = $summary->withAmount(new Amount('1'));

        $this->assertFalse($summary->isBalanced());
    }

    public function testBalancedSummary()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('1'));
        $summary = $summary->withAmount(new Amount('-1'));

        $this->assertTrue($summary->isBalanced());
    }

    public function testBalancedSummaryWithOnlyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $this->assertTrue($summary->isBalanced());
    }

    public function testExceptionGetMagnitudeOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getMagnitude();
    }

    public function testExceptionOnGetMagnitudeOnUnbalancedSummary()
    {
        $this->expectException(SummaryNotBalancedException::class);
        $summary = new Summary();
        $summary = $summary->withAmount(new Amount('5'));
        $summary->getMagnitude();
    }

    public function testGetMagnitude()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('1'));
        $summary = $summary->withAmount(new Amount('-1'));

        $this->assertTrue($summary->getMagnitude()->equals(new Amount('1')));
    }

    public function testGetMagnitudeOnSummaryWithOnlyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $this->assertTrue($summary->getMagnitude()->equals(new Amount('0')));
    }

    public function testBasicSummary()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('10'));
        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testPositiveBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('10'));
        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('15'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(new Amount('5'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('10'));
        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('-5'));
        $summary = $summary->withAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(new Amount('10'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('-10'));
        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('-10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('-10'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('5'));
        $summary = $summary->withAmount(new Amount('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(new Amount('0'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(new Amount('0'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(new Amount('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(new Amount('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(new Amount('5'))
        );
    }

    public function testWithSummaryAmounts()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withAmount(new Amount('5'));

        $summary = new Summary();

        $summary = $summary->withAmount(new Amount('5'));

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getDebitTotal()->equals(new Amount('10')));
    }

    public function testWithSummaryIncomingBalanceKeptInNew()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $summary = $summary->withSummary(new Summary());

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('1')));
    }

    public function testWithSummaryIncomingBalanceOnlyInAddedSummary()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withIncomingBalance(new Amount('1'));

        $summary = new Summary();

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('1')));
    }

    public function testWithSummaryIncomingBalanceInBoth()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withIncomingBalance(new Amount('1'));

        $summary = new Summary();

        $summary = $summary->withIncomingBalance(new Amount('1'));

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('2')));
    }

    public function testFromAmount()
    {
        $summary = Summary::fromAmount(new Amount('1'));

        $this->assertTrue($summary->getDebitTotal()->equals(new Amount('1')));
    }

    public function testFromIncomingBalance()
    {
        $summary = Summary::fromIncomingBalance(new Amount('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(new Amount('1')));
    }
}
