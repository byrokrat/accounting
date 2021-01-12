<?php

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use Money\Money;

class DimensionTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testAttributes()
    {
        $attributable = new Dimension('');

        $this->assertFalse($attributable->hasAttribute('does-not-exist'));

        $this->assertSame('', $attributable->getAttribute('does-not-exist'));

        $attributable->setAttribute('foo', 'bar');

        $this->assertTrue($attributable->hasAttribute('foo'));

        $this->assertSame('bar', $attributable->getAttribute('foo'));

        $this->assertSame(['foo' => 'bar'], $attributable->getAttributes());
    }

    public function testGetId()
    {
        $this->assertSame(
            '1234',
            (new Dimension(id: '1234'))->getId()
        );
    }

    public function testDescription()
    {
        $this->assertSame(
            'foo',
            (new Dimension(id: '', description: 'foo'))->getDescription()
        );
    }

    public function testChildren()
    {
        $child = new Dimension(id: '');
        $dim = new Dimension(id: '', children: [$child]);

        $this->assertTrue($dim->hasChildren());
        $this->assertSame([$child], $dim->getChildren());
        $this->assertSame([$child], $dim->getItems());
    }

    public function testNoChildren()
    {
        $dim = new Dimension('');

        $this->assertFalse($dim->hasChildren());
        $this->assertSame([], $dim->getChildren());
        $this->assertSame([], $dim->getItems());
    }

    public function testAttributesToConstructor()
    {
        $this->assertSame(
            'bar',
            (new Dimension(id: '0', attributes: ['foo' => 'bar']))->getAttribute('foo')
        );
    }

    public function testAddChild()
    {
        $child1 = new Dimension('child1');
        $child2 = new Dimension('child2');

        $dimension = new Dimension(
            id: 'parent',
            children: [$child1],
        );

        $dimension->addChild($child2);

        $this->assertSame([$child1, $child2], $dimension->getChildren());
    }

    public function testChildrenIncludedInSummary()
    {
        $child = $this->prophesize(DimensionInterface::class);
        $child->getSummary()->willReturn(Summary::fromAmount(Money::SEK('100')));

        $dim = new Dimension(id: '', children: [$child->reveal()]);

        $this->assertTrue($dim->getSummary()->getDebitTotal()->equals(Money::SEK('100')));
    }

    public function testAddTransactions()
    {
        $trans = $this->createMock(TransactionInterface::class);

        $dim = new Dimension(id: '');

        $dim->addTransaction($trans);

        $this->assertSame([$trans], $dim->getTransactions());
    }

    public function testTransactionsIncludedInSummary()
    {
        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getSummary()->willReturn(Summary::fromAmount(Money::SEK('100')));

        $dim = new Dimension(id: '');

        $dim->addTransaction($trans->reveal());

        $this->assertTrue($dim->getSummary()->getDebitTotal()->equals(Money::SEK('100')));
    }

    public function testIncomingBalance()
    {
        $dim = new Dimension(id: '');

        $dim->setIncomingBalance(Money::SEK('100'));

        $this->assertTrue($dim->getSummary()->getIncomingBalance()->equals(Money::SEK('100')));
    }

    public function testSummaryFromAllChannels()
    {
        $trans = $this->prophesize(TransactionInterface::class);
        $trans->getSummary()->willReturn(Summary::fromAmount(Money::SEK('50')));

        $child = $this->prophesize(DimensionInterface::class);
        $child->getSummary()->willReturn(Summary::fromAmount(Money::SEK('-50')));

        $dim = new Dimension(id: '');

        $dim->setIncomingBalance(Money::SEK('100'));

        $dim->addTransaction($trans->reveal());

        $dim->addChild($child->reveal());

        $this->assertTrue($dim->getSummary()->getOutgoingBalance()->equals(Money::SEK('100')));
        $this->assertTrue($dim->getSummary()->getMagnitude()->equals(Money::SEK('50')));
    }
}
