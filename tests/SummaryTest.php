<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\SummaryEmptyException;
use byrokrat\accounting\Exception\SummaryNotBalancedException;
use Money\Money;

class SummaryTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptySummaryIsEmpty()
    {
        $this->assertTrue((new Summary())->isEmpty());
    }

    public function testNotEmptyWhenIncomingBalanceIsSet()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $this->assertFalse($summary->isEmpty());
    }

    public function testNotEmptyWhenAmountIsSet()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('1'));

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

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('1')));
    }

    public function testGetEmptyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('0')));
    }

    public function testExceptionGetOutgoingBalanceOnEmptySummary()
    {
        $this->expectException(SummaryEmptyException::class);
        (new Summary())->getOutgoingBalance();
    }

    public function testGetOutgoingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $summary = $summary->withAmount(Money::SEK('1'));

        $this->assertTrue($summary->getOutgoingBalance()->equals(Money::SEK('2')));
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

        $summary = $summary->withAmount(Money::SEK('1'));

        $this->assertTrue($summary->getDebitTotal()->equals(Money::SEK('1')));
        $this->assertTrue($summary->getCreditTotal()->equals(Money::SEK('0')));
    }

    public function testGetDebitAndCreditOnNegativeAmount()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('-1'));

        $this->assertTrue($summary->getDebitTotal()->equals(Money::SEK('0')));
        $this->assertTrue($summary->getCreditTotal()->equals(Money::SEK('1')));
    }

    public function testEmptySummaryIsConsideredBalanced()
    {
        $this->assertTrue((new Summary())->isBalanced());
    }

    public function testNotBalancedSummary()
    {
        $summary = (new Summary());

        $summary = $summary->withAmount(Money::SEK('1'));

        $this->assertFalse($summary->isBalanced());
    }

    public function testBalancedSummary()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('1'));
        $summary = $summary->withAmount(Money::SEK('-1'));

        $this->assertTrue($summary->isBalanced());
    }

    public function testBalancedSummaryWithOnlyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

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
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary->getMagnitude();
    }

    public function testGetMagnitude()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('1'));
        $summary = $summary->withAmount(Money::SEK('-1'));

        $this->assertTrue($summary->getMagnitude()->equals(Money::SEK('1')));
    }

    public function testGetMagnitudeOnSummaryWithOnlyIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $this->assertTrue($summary->getMagnitude()->equals(Money::SEK('0')));
    }

    public function testBasicSummary()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('10'));
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(Money::SEK('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(Money::SEK('10'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(Money::SEK('5'))
        );
    }

    public function testPositiveBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('10'));
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(Money::SEK('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(Money::SEK('15'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(Money::SEK('10'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(Money::SEK('5'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('10'));
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('-5'));
        $summary = $summary->withAmount(Money::SEK('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(Money::SEK('10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(Money::SEK('5'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(Money::SEK('10'))
        );

        $this->assertFalse($summary->isBalanced());
    }

    public function testNegativeIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('-10'));
        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(Money::SEK('-10'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(Money::SEK('-10'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(Money::SEK('5'))
        );
    }

    public function testWithoutIncomingBalance()
    {
        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('5'));
        $summary = $summary->withAmount(Money::SEK('-5'));

        $this->assertTrue(
            $summary->getIncomingBalance()->equals(Money::SEK('0'))
        );

        $this->assertTrue(
            $summary->getOutgoingBalance()->equals(Money::SEK('0'))
        );

        $this->assertTrue(
            $summary->getDebitTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue(
            $summary->getCreditTotal()->equals(Money::SEK('5'))
        );

        $this->assertTrue($summary->isBalanced());

        $this->assertTrue(
            $summary->getMagnitude()->equals(Money::SEK('5'))
        );
    }

    public function testWithSummaryAmounts()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withAmount(Money::SEK('5'));

        $summary = new Summary();

        $summary = $summary->withAmount(Money::SEK('5'));

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getDebitTotal()->equals(Money::SEK('10')));
    }

    public function testWithSummaryIncomingBalanceKeptInNew()
    {
        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $summary = $summary->withSummary(new Summary());

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('1')));
    }

    public function testWithSummaryIncomingBalanceOnlyInAddedSummary()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withIncomingBalance(Money::SEK('1'));

        $summary = new Summary();

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('1')));
    }

    public function testWithSummaryIncomingBalanceInBoth()
    {
        $addedSummary = new Summary();

        $addedSummary = $addedSummary->withIncomingBalance(Money::SEK('1'));

        $summary = new Summary();

        $summary = $summary->withIncomingBalance(Money::SEK('1'));

        $summary = $summary->withSummary($addedSummary);

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('2')));
    }

    public function testFromAmount()
    {
        $summary = Summary::fromAmount(Money::SEK('1'));

        $this->assertTrue($summary->getDebitTotal()->equals(Money::SEK('1')));
    }

    public function testFromIncomingBalance()
    {
        $summary = Summary::fromIncomingBalance(Money::SEK('1'));

        $this->assertTrue($summary->getIncomingBalance()->equals(Money::SEK('1')));
    }
}
