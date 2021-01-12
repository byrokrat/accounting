<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidDimensionException;
use byrokrat\accounting\Exception\InvalidVerificationException;
use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\amount\Amount;
use Prophecy\Argument;

class QueryTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    private function accountingMock(
        string $type = '',
        string $id = '',
        array $items = [],
        Summary $summary = null,
        array $attributes = [],
    ): AccountingObjectInterface {
        $item = $this->prophesize($type ?: AccountingObjectInterface::class);

        $item->getId()->willReturn($id);
        $item->getItems()->willReturn($items);
        $item->getSummary()->willReturn($summary ?: new Summary());

        foreach ($attributes as $key => $value) {
            $item->hasAttribute($key)->willReturn(true);
            $item->getAttribute($key)->willReturn($value);
        }

        $item->hasAttribute(Argument::any())->willReturn(false);

        return $item->reveal();
    }

    public function testExceptionOnInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        iterator_to_array(new Query(['this-is-not-an-accouting-object']));
    }

    public function testAsArray()
    {
        $query = new Query([$item = $this->accountingMock()]);

        $this->assertSame([$item], $query->asArray());
    }

    public function testAsContainer()
    {
        $query = new Query([$item = $this->accountingMock()]);

        $this->assertEquals(new Container($item), $query->asContainer());
    }

    public function testAsSummary()
    {
        $item1 = $this->accountingMock(
            type: TransactionInterface::class,
            id: '1',
            summary: Summary::fromAmount(new Amount('50'))
        );

        $item2 = $this->accountingMock(
            type: TransactionInterface::class,
            id: '2',
            summary: Summary::fromAmount(new Amount('150'))
        );

        $query = new Query([$item1, $item2]);

        $this->assertTrue(
            $query->asSummary()->getOutgoingBalance()->equals(new Amount('200'))
        );
    }

    /**
     * @depends testAsArray
     */
    public function testNestedIteration()
    {
        $query = new Query([
            $item1 = $this->accountingMock(
                items: [
                    $item2 = $this->accountingMock(
                        items: [
                            $item3 = $this->accountingMock()
                        ]
                    )
                ]
            )
        ]);

        $this->assertSame(
            [$item1, $item2, $item3],
            $query->asArray(),
            'Nested iteration should yield values bottom down'
        );

        $this->assertSame(
            [$item1, $item2, $item3],
            $query->asArray(),
            'Query should be rewindable and yield the same results the second time'
        );
    }

    /**
     * @depends testAsArray
     */
    public function testFilter()
    {
        $query = new Query([
            $item = $this->accountingMock(),
            $trans = $this->accountingMock(type: TransactionInterface::class),
        ]);

        $this->assertSame(
            [$trans],
            $query->filter(fn($item) => $item instanceof TransactionInterface)->asArray(),
            'Only filtered objects should be returned'
        );

        $this->assertSame(
            [$item, $trans],
            $query->asArray(),
            'Original query should not be affected'
        );
    }

    public function testFirst()
    {
        $query = new Query([
            $first = $this->accountingMock(),
            $last = $this->accountingMock(),
        ]);

        $this->assertSame($first, $query->first());
    }

    /**
     * @depends testFirst
     * @depends testFilter
     */
    public function testChaingFilterAndFirst()
    {
        $query = new Query([
            $this->accountingMock(),
            $trans = $this->accountingMock(type: TransactionInterface::class),
        ]);

        $this->assertSame(
            $trans,
            $query->filter(fn($item) => $item instanceof TransactionInterface)->first()
        );
    }

    public function testFirstWithNoItems()
    {
        $this->assertNull((new Query())->first());
    }

    public function testLast()
    {
        $query = new Query([
            $first = $this->accountingMock(),
            $last = $this->accountingMock(),
        ]);

        $this->assertSame($last, $query->last());
    }

    public function testLastWithNoItems()
    {
        $this->assertNull((new Query())->last());
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            (new Query([]))->isEmpty()
        );

        $this->assertFalse(
            (new Query([$this->accountingMock()]))->isEmpty()
        );
    }

    /**
     * @depends testIsEmpty
     * @depends testFilter
     */
    public function testChaingFilterAndEmpty()
    {
        $query = new Query([$this->accountingMock(type: AccountingObjectInterface::class)]);

        $this->assertTrue(
            $query->filter(fn($item) => $item instanceof TransactionInterface)->isEmpty()
        );

        $this->assertFalse(
            $query->filter(fn($item) => $item instanceof AccountingObjectInterface)->isEmpty()
        );
    }

    public function testCountable()
    {
        $query = new Query([$this->accountingMock(), $this->accountingMock()]);

        $this->assertCount(2, $query);
    }

    /**
     * @depends testCountable
     * @depends testFilter
     */
    public function testCountingFilteredValues()
    {
        $query = new Query([$this->accountingMock(type: AccountingObjectInterface::class)]);

        $this->assertCount(
            0,
            $query->filter(fn($item) => $item instanceof TransactionInterface)
        );
    }

    public function testAccount()
    {
        $query = new Query([
            $account = $this->accountingMock(
                type: AccountInterface::class,
                id: '1234'
            )
        ]);

        $this->assertSame($account, $query->account('1234'));
    }

    public function testExceptionOnUnknownAccount()
    {
        $query = new Query([
            $this->accountingMock(
                type: DimensionInterface::class,
                id: '1234'
            )
        ]);

        $this->expectException(InvalidAccountException::class);

        $query->account('1234');
    }

    /**
     * @depends testAsArray
     */
    public function testAccounts()
    {
        $query = new Query([
            $account1 = $this->accountingMock(type: AccountInterface::class, id: '1'),
            $account1,
            $account2 = $this->accountingMock(type: AccountInterface::class, id: '2'),
            $this->accountingMock(type: TransactionInterface::class),
        ]);

        $this->assertSame(
            [$account1, $account2],
            $query->accounts()->asArray()
        );
    }

    public function testDimension()
    {
        $query = new Query([
            $dimension = $this->accountingMock(
                type: DimensionInterface::class,
                id: '1234'
            )
        ]);

        $this->assertSame($dimension, $query->dimension('1234'));
    }

    public function testExceptionOnUnknownDimensionNumber()
    {
        $this->expectException(InvalidDimensionException::class);
        (new Query())->dimension('1234');
    }

    /**
     * @depends testAsArray
     */
    public function testDimensions()
    {
        $query = new Query([
            $dimension1 = $this->accountingMock(type: DimensionInterface::class, id: '1'),
            $dimension1,
            $dimension2 = $this->accountingMock(type: DimensionInterface::class, id: '2'),
            $this->accountingMock(type: TransactionInterface::class),
        ]);

        $this->assertSame(
            [$dimension1, $dimension2],
            $query->dimensions()->asArray()
        );
    }

    /**
     * @depends testAsArray
     */
    public function testTransactions()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
            $trans1 = $this->accountingMock(type: TransactionInterface::class, id: '1'),
            $trans1,
            $trans2 = $this->accountingMock(type: TransactionInterface::class, id: '2'),
        ]);

        $this->assertSame(
            [$trans1, $trans2],
            $query->transactions()->asArray()
        );
    }

    public function testVerification()
    {
        $query = new Query([
            $verification = $this->accountingMock(
                type: VerificationInterface::class,
                id: '1234'
            )
        ]);

        $this->assertSame($verification, $query->verification('1234'));
    }

    public function testExceptionOnUnknownVerification()
    {
        $this->expectException(InvalidVerificationException::class);
        (new Query())->verification('1234');
    }

    /**
     * @depends testAsArray
     */
    public function testVerifications()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
            $verification1 = $this->accountingMock(type: VerificationInterface::class, id: '1'),
            $verification1,
            $verification2 = $this->accountingMock(type: VerificationInterface::class, id: '2'),
        ]);

        $this->assertSame(
            [$verification1, $verification2],
            $query->verifications()->asArray()
        );
    }

    public function testEach()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
            $this->accountingMock(type: VerificationInterface::class),
        ]);

        $str = '';

        $query->each(function ($item) use (&$str) {
            $str .= get_class($item);
        });

        $this->assertMatchesRegularExpression('/DimensionInterface/', $str);
        $this->assertMatchesRegularExpression('/VerificationInterface/', $str);
    }

    public function testLazyEach()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
        ]);

        $str = '';

        $query->lazyEach(function ($item) use (&$str) {
            $str .= get_class($item);
        })->exec();

        $this->assertMatchesRegularExpression('/DimensionInterface/', $str);
    }

    public function testLazyOn()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
            $this->accountingMock(type: VerificationInterface::class),
        ]);

        $str = '';

        $query->lazyOn(
            fn($item) => $item instanceof DimensionInterface,
            function ($item) use (&$str) {
                $str .= get_class($item);
            }
        )->exec();

        $this->assertMatchesRegularExpression('/DimensionInterface/', $str);
        $this->assertDoesNotMatchRegularExpression('/VerificationInterface/', $str);
    }

    /**
     * @depends testAsArray
     */
    public function testMap()
    {
        $dimension = $this->accountingMock(type: DimensionInterface::class);
        $verification = $this->accountingMock(type: VerificationInterface::class);

        $query = new Query([$dimension, $dimension]);

        $this->assertSame(
            [$verification, $verification],
            $query->map(fn($item) => $verification)->asArray(),
            'Map should alter all items in query'
        );

        $this->assertSame(
            [$dimension, $dimension],
            $query->asArray(),
            'Original query should still be the same'
        );
    }

    /**
     * @depends testAsArray
     */
    public function testOrderBy()
    {
        $query = new Query([
            $verification = $this->accountingMock(type: VerificationInterface::class),
            $transaction = $this->accountingMock(type: TransactionInterface::class),
            $dimension = $this->accountingMock(type: DimensionInterface::class),
        ]);

        $this->assertSame(
            [$dimension, $transaction, $verification],
            $query->orderBy(fn($left, $right) => get_class($left) <=> get_class($right))->asArray()
        );
    }

    public function testOrderById()
    {
        $query = new Query([
            $dimension = $this->accountingMock(type: DimensionInterface::class, id: '3'),
            $transaction = $this->accountingMock(type: TransactionInterface::class, id: '2'),
            $verification = $this->accountingMock(type: VerificationInterface::class, id: '1'),
        ]);

        $this->assertSame(
            [$verification, $transaction, $dimension],
            $query->orderById()->asArray()
        );
    }

    public function testReduce()
    {
        $query = new Query([
            $this->accountingMock(type: DimensionInterface::class),
            $this->accountingMock(type: VerificationInterface::class),
        ]);

        $str = $query->reduce(fn($carry, $item) => $carry . get_class($item));

        $this->assertMatchesRegularExpression('/DimensionInterface/', $str);
        $this->assertMatchesRegularExpression('/VerificationInterface/', $str);
    }

    /**
     * @depends testReduce
     */
    public function testReduceWithInitialValue()
    {
        $query = new Query([
            $this->accountingMock(),
            $this->accountingMock(),
            $this->accountingMock(),
        ]);

        $this->assertSame(
            'fooXXX',
            $query->reduce(fn($carry, $item) => $carry . 'X', 'foo')
        );
    }

    /**
     * @depends testAsArray
     */
    public function testUnique()
    {
        $item1 = $this->accountingMock(id: '1');
        $item2 = $this->accountingMock(id: '2');

        $query = new Query([$item1, $item1, $item2]);

        $this->assertSame(
            [$item1, $item2],
            $query->unique()->asArray()
        );
    }

    /**
     * @depends testNestedIteration
     */
    public function testWhere()
    {
        $query = new Query([
            $verA = $this->accountingMock(
                type: VerificationInterface::class,
                items: [$trans = $this->accountingMock(type: TransactionInterface::class)]
            ),
            $verB = $this->accountingMock(
                type: VerificationInterface::class,
                items: []
            ),
        ]);

        $this->assertSame(
            [$verA, $trans],
            $query->where(fn($item) => $item instanceof TransactionInterface)->asArray(),
            'Get items that is, or contains, a transaction'
        );
    }

    /**
     * @depends testNestedIteration
     */
    public function testWhereNot()
    {
        $query = new Query([
            $verA = $this->accountingMock(
                type: VerificationInterface::class,
                items: [$trans = $this->accountingMock(type: TransactionInterface::class)]
            ),
            $verB = $this->accountingMock(
                type: VerificationInterface::class,
                items: []
            ),
        ]);

        $this->assertSame(
            [$verB],
            $query->whereNot(fn($item) => $item instanceof TransactionInterface)->asArray(),
            'Get items that are not, and does not contain, a transaction'
        );
    }

    public function testWhereAttribute()
    {
        $attributable = $this->accountingMock(attributes: ['A' => 'A']);

        $query = new Query([$attributable]);

        $this->assertSame([$attributable], $query->whereAttribute('A')->asArray());
        $this->assertSame([], $query->whereAttribute('B')->asArray());
    }

    public function testWhereAttributeValue()
    {
        $attributable = $this->accountingMock(attributes: ['A' => 'value']);

        $query = new Query([$attributable]);

        $this->assertSame([$attributable], $query->whereAttributeValue('A', 'value')->asArray());
        $this->assertSame([], $query->whereAttributeValue('A', 'not-value')->asArray());
        $this->assertSame([], $query->whereAttributeValue('B', 'foobar')->asArray());
    }

    /**
     * @depends testWhere
     */
    public function testWhereAccount()
    {
        $query = new Query([
            $trans1 = $this->accountingMock(
                items: [$account1 = $this->accountingMock(type: AccountInterface::class, id: '1')]
            ),
            $trans2 = $this->accountingMock(
                items: [$account2 = $this->accountingMock(type: AccountInterface::class, id: '2')]
            ),
        ]);

        $this->assertSame(
            [$trans1, $account1],
            $query->whereAccount('1')->asArray(),
            'Get items that are, or contains, account number 1'
        );
    }

    private function whereAmountQuery(): Query
    {
        $transA = $this->prophesize(TransactionInterface::class);
        $transA->getAmount()->willReturn(new Amount('4'));
        $transA->getItems()->willReturn([]);
        $transA = $transA->reveal();

        $transB = $this->prophesize(TransactionInterface::class);
        $transB->getAmount()->willReturn(new Amount('2'));
        $transB->getItems()->willReturn([]);
        $transB = $transB->reveal();

        $transC = $this->prophesize(TransactionInterface::class);
        $transC->getAmount()->willReturn(new Amount('3'));
        $transC->getItems()->willReturn([]);
        $transC = $transC->reveal();

        $transD = $this->prophesize(TransactionInterface::class);
        $transD->getAmount()->willReturn(new Amount('1'));
        $transD->getItems()->willReturn([]);
        $transD = $transD->reveal();

        return new Query([$transA, $transB, $transC, $transD]);
    }

    /**
     * @depends testWhere
     */
    public function testWhereAmountEquals()
    {
        $this->assertCount(
            1,
            $this->whereAmountQuery()->whereAmountEquals(new Amount('4'))
        );

        $this->assertCount(
            1,
            $this->whereAmountQuery()->whereAmountEquals(new Amount('3'))
        );
    }

    /**
     * @depends testWhere
     */
    public function testWhereAmountIsGreaterThan()
    {
        $this->assertCount(
            2,
            $this->whereAmountQuery()->whereAmountIsGreaterThan(new Amount('2'))
        );

        $this->assertCount(
            1,
            $this->whereAmountQuery()->whereAmountIsGreaterThan(new Amount('3'))
        );
    }

    /**
     * @depends testWhere
     */
    public function testWhereAmountIsLessThan()
    {
        $this->assertCount(
            1,
            $this->whereAmountQuery()->whereAmountIsLessThan(new Amount('2'))
        );

        $this->assertCount(
            2,
            $this->whereAmountQuery()->whereAmountIsLessThan(new Amount('3'))
        );
    }

    /**
     * @depends testAsArray
     */
    public function testLimit()
    {
        $query = new Query([
            $one = $this->accountingMock(),
            $two = $this->accountingMock(),
            $three = $this->accountingMock(),
            $four = $this->accountingMock(),
        ]);

        $this->assertSame(
            [$one, $two],
            $query->limit(2)->asArray()
        );

        $this->assertSame(
            [$one, $two, $three, $four],
            $query->limit(10)->asArray()
        );

        $this->assertSame(
            [$two, $three],
            $query->limit(2, 1)->asArray()
        );

        $this->assertSame(
            [$three, $four],
            $query->limit(100, 2)->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testMacro()
    {
        Query::macro('whereId', function ($id) {
            return $this->filter(fn($item) => $item->getId() == $id);
        });

        $query = new Query([
            $item1 = $this->accountingMock(id: '1'),
            $item2 = $this->accountingMock(id: '2'),
        ]);

        $this->assertSame(
            [$item1],
            $query->whereId('1')->asArray()
        );
    }

    public function testExceptionWhenOverwritingMethodWithMacro()
    {
        $this->expectException(RuntimeException::class);
        Query::macro('filter', fn() => true);
    }

    public function testExceptionWhenOverwritingMacro()
    {
        $this->expectException(RuntimeException::class);
        Query::macro('thisRareMacroNameIsCreated', fn() => true);
        Query::macro('thisRareMacroNameIsCreated', fn() => true);
    }

    public function testExceptionOnUndefinedMethodCall()
    {
        $this->expectException(RuntimeException::class);
        (new Query())->thisMethodDoesNotExist();
    }
}
