<?php

declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\amount\Amount;

class QueryTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testAsArray()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, 2, 3]))->asArray()
        );
    }

    public function testAsContainer()
    {
        $this->assertEquals(
            new Container(1, 2, 3),
            (new Query([1, 2, 3]))->asContainer()
        );
    }

    public function testAsSummary()
    {
        $trans = $this->prophesize(TransactionInterface::CLASS);
        $trans->getAmount()->willReturn(new Amount('50'));
        $trans->select()->willReturn(new Query());
        $trans = $trans->reveal();

        $this->assertTrue(
            (new Query([1, $trans, $trans]))->asSummary()->getOutgoingBalance()->equals(new Amount('100'))
        );
    }

    /**
     * @depends testAsArray
     */
    public function testNestedIteration()
    {
        $queryable1 = $this->prophesize(QueryableInterface::CLASS);
        $queryable1->select()->willReturn(new Query(['bar']));
        $queryable1 = $queryable1->reveal();

        $queryable2 = $this->prophesize(QueryableInterface::CLASS);
        $queryable2->select()->willReturn(new Query(['foo', $queryable1]));
        $queryable2 = $queryable2->reveal();

        $query = new Query(['before', $queryable2, 'after']);

        $this->assertEquals(
            ['before', $queryable2, 'foo', $queryable1, 'bar', 'after'],
            $query->asArray(),
            'Nested iteration should yield values bottom down'
        );

        $this->assertEquals(
            ['before', $queryable2, 'foo', $queryable1, 'bar', 'after'],
            $query->asArray(),
            'Query should be rewindable and yield the same results the second time'
        );
    }

    /**
     * @depends testNestedIteration
     */
    public function testFilter()
    {
        $queryable = $this->prophesize(QueryableInterface::CLASS);
        $queryable->select()->willReturn(new Query([2]));

        $this->assertSame(
            [1, 2, 3],
            (new Query([1, $queryable->reveal(), 3]))->filter('is_integer')->asArray()
        );
    }

    public function testFilterType()
    {
        $this->assertSame(
            [$query = new Query()],
            (new Query([1, $query]))->filterType(Query::CLASS)->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testThatFilterCreatesNewQuery()
    {
        $query = new Query([1, 'A', 2]);

        $this->assertSame(
            [1, 2],
            $query->filter('is_integer')->asArray()
        );

        $this->assertSame(
            [1, 'A', 2],
            $query->asArray()
        );
    }

    public function testFirst()
    {
        $this->assertSame(
            1,
            (new Query([1, 2, 3]))->getFirst()
        );
    }

    /**
     * @depends testFirst
     * @depends testFilter
     */
    public function testFirstFiltered()
    {
        $this->assertSame(
            3,
            (new Query(['A', false, 3]))->filter('is_integer')->getFirst()
        );
    }

    public function testFirstWithNoItems()
    {
        $this->assertNull((new Query())->getFirst());
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            (new Query([]))->isEmpty()
        );

        $this->assertFalse(
            (new Query([1]))->isEmpty()
        );
    }

    /**
     * @depends testIsEmpty
     * @depends testFilter
     */
    public function testIsEmptyFiltered()
    {
        $this->assertTrue(
            (new Query(['A', null]))->filter('is_integer')->isEmpty()
        );

        $this->assertFalse(
            (new Query([1]))->filter('is_integer')->isEmpty()
        );
    }

    public function testContains()
    {
        $this->assertTrue((new Query(['A']))->contains('A'));
        $this->assertFalse((new Query(['A']))->contains('B'));
    }

    public function testCountable()
    {
        $this->assertSame(
            3,
            count(new Query([1, 2, 3]))
        );
    }

    /**
     * @depends testCountable
     * @depends testFilter
     */
    public function testCountingFilteredValues()
    {
        $queryable = $this->prophesize(QueryableInterface::CLASS);
        $queryable->select()->willReturn(new Query([2]));

        $this->assertSame(
            3,
            count((new Query([1, $queryable->reveal(), 3]))->filter('is_integer'))
        );
    }

    /**
     * @depends testFilter
     */
    public function testAccounts()
    {
        $account = $this->createMock(AccountInterface::CLASS);

        $this->assertSame(
            [$account, $account],
            (new Query([1, $account, $account, 3]))->accounts()->asArray()
        );
    }

    /**
     * @depends testAccounts
     */
    public function testuniqueAccounts()
    {
        $account = $this->createMock(AccountInterface::CLASS);

        $this->assertSame(
            [$account],
            (new Query([1, $account, $account, 3]))->uniqueAccounts()->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testDimensions()
    {
        $dim = $this->createMock(DimensionInterface::CLASS);

        $this->assertSame(
            [$dim, $dim],
            (new Query([1, $dim, $dim, 3]))->dimensions()->asArray()
        );
    }

    /**
     * @depends testDimensions
     */
    public function testUniqueDimensions()
    {
        $dim = $this->createMock(DimensionInterface::CLASS);

        $this->assertSame(
            [$dim],
            (new Query([1, $dim, $dim, 3]))->uniqueDimensions()->asArray()
        );
    }

    public function testWhereAttribute()
    {
        $attributableProphecy = $this->prophesize(AttributableInterface::CLASS);

        $attributableProphecy->hasAttribute('A')->willReturn(true);
        $attributableProphecy->getAttribute('A')->willReturn('foobar');
        $attributableProphecy->hasAttribute('B')->willReturn(false);

        $attributable = $attributableProphecy->reveal();

        $this->assertSame(
            [$attributable],
            (new Query([1, $attributable, 3]))->whereAttribute('A')->asArray()
        );

        $this->assertSame(
            [],
            (new Query([1, $attributable, 3]))->whereAttribute('B')->asArray()
        );

        $this->assertSame(
            [$attributable],
            (new Query([1, $attributable, 3]))->whereAttribute('A', 'foobar')->asArray()
        );

        $this->assertSame(
            [],
            (new Query([1, $attributable, 3]))->whereAttribute('A', 'not-foobar')->asArray()
        );
    }

    public function testQueryIsQueryable()
    {
        $this->assertSame(
            $query = new Query(),
            $query->select()
        );
    }

    /**
     * @depends testFilter
     */
    public function testTransactions()
    {
        $this->assertSame(
            [$transaction = $this->createMock(TransactionInterface::CLASS)],
            (new Query([1, $transaction, 3]))->transactions()->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testVerifications()
    {
        $verification = $this->createMock(VerificationInterface::CLASS);

        $this->assertSame(
            [$verification, $verification],
            (new Query([1, $verification, $verification, 3]))->verifications()->asArray()
        );
    }

    /**
     * @depends testVerifications
     */
    public function testUniqueVerifications()
    {
        $verification = $this->createMock(VerificationInterface::CLASS);

        $this->assertSame(
            [$verification],
            (new Query([1, $verification, $verification, 3]))->uniqueVerifications()->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testUnbalancedVerifications()
    {
        $balanced = $this->prophesize(VerificationInterface::CLASS);
        $balanced->isBalanced()->willReturn(true);
        $balanced->select()->willReturn(new Query());
        $balanced->getVerificationId()->willReturn(1);
        $balanced->getAttribute('series', '')->willReturn('');
        $balanced = $balanced->reveal();

        $unbalanced = $this->prophesize(VerificationInterface::CLASS);
        $unbalanced->isBalanced()->willReturn(false);
        $unbalanced->select()->willReturn(new Query());
        $unbalanced->getVerificationId()->willReturn(2);
        $unbalanced->getAttribute('series', '')->willReturn('');
        $unbalanced = $unbalanced->reveal();

        $this->assertSame(
            [$unbalanced],
            (new Query([1, $balanced, $unbalanced, 3]))->unbalancedVerifications()->asArray()
        );
    }

    public function testEach()
    {
        $str = '';

        (new Query(['A', 'B', 'C']))->each(function ($letter) use (&$str) {
            $str .= $letter;
        });

        $this->assertSame('ABC', $str);
    }

    public function testLazyOn()
    {
        $sum = 0;

        (new Query([5, 'B', 5]))->lazyOn(
            function ($item) {
                    return is_integer($item);
            },
            function (int $integer) use (&$sum) {
                $sum += $integer;
            }
        )->exec();

        $this->assertSame(10, $sum);
    }

    /**
     * @depends testAsArray
     */
    public function testMap()
    {
        $this->assertSame(
            [10, 20],
            (new Query([0, 10]))->map(function ($integer) {
                return $integer + 10;
            })->asArray()
        );
    }

    /**
     * @depends testMap
     */
    public function testThatMapReturnesNewQuery()
    {
        $query = new Query([0, 10]);

        $this->assertSame(
            [10, 20],
            $query->map(function ($integer) {
                return $integer + 10;
            })->asArray()
        );

        $this->assertSame(
            [0, 10],
            $query->asArray()
        );
    }

    /**
     * @depends testAsArray
     */
    public function testOrderBy()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([2, 3, 1]))->orderBy(function ($left, $right) {
                return $left <=> $right;
            })->asArray()
        );
    }

    public function testReduce()
    {
        $this->assertSame(
            'ABC',
            (new Query(['A', 'B', 'C']))->reduce(function ($carry, $item) {
                return $carry . $item;
            })
        );
    }

    /**
     * @depends testReduce
     */
    public function testReduceWithInitialValue()
    {
        $this->assertSame(
            'foobar',
            (new Query(['b', 'a', 'r']))->reduce(function ($carry, $item) {
                return $carry . $item;
            }, 'foo')
        );
    }

    /**
     * @depends testAsArray
     */
    public function testWhereUnique()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, 2, 3, 2]))->whereUnique()->asArray()
        );
    }

    /**
     * @depends testWhereUnique
     */
    public function testUniqueWithObjectItems()
    {
        $objA = (object)[];
        $objB = (object)[];

        $this->assertSame(
            [$objA, $objB],
            (new Query([$objA, $objB, $objB, $objA]))->whereUnique()->asArray()
        );
    }

    /**
     * @depends testWhereUnique
     */
    public function testUniqueWithInspector()
    {
        $arrAA = ['A', 'A'];
        $arrAB = ['A', 'B'];
        $arrBA = ['B', 'A'];
        $arrBB = ['B', 'B'];

        $inspector = function ($arr) {
            return $arr[0];
        };

        $this->assertSame(
            [$arrAA, $arrBA],
            (new Query([$arrAA, $arrBA, $arrAB, $arrBB]))->whereUnique($inspector)->asArray()
        );
    }

    /**
     * @depends testAsArray
     */
    public function testWhere()
    {
        $foo = new Query(['', 'foo']);
        $bar = new Query(['', 'bar']);

        $this->assertSame(
            [$foo],
            (new Query([$foo, $bar]))->filterType(QueryableInterface::CLASS)->where(function ($item) {
                return is_string($item) && $item == 'foo';
            })->asArray(),
            '$bar should be removed as it does not contain the subitem foo'
        );
    }

    /**
     * @depends testAsArray
     */
    public function testWhereNot()
    {
        $foo = new Query(['', 'foo']);
        $bar = new Query(['', 'bar']);

        $this->assertEquals(
            [$bar],
            (new Query([$foo, $bar]))->filterType(QueryableInterface::CLASS)->whereNot(function ($item) {
                return is_string($item) && $item == 'foo';
            })->asArray(),
            '$bar should be kept as it does not contain the subitem foo'
        );
    }

    /**
     * @depends testWhere
     */
    public function testWhereAccount()
    {
        $account1 = $this->prophesize(AccountInterface::CLASS);
        $account1->getId()->willReturn('1');
        $account1->select()->willReturn(new Query());
        $query1 = new Query([$account1->reveal()]);

        $account2 = $this->prophesize(AccountInterface::CLASS);
        $account2->getId()->willReturn('2');
        $account2->select()->willReturn(new Query());
        $query2 = new Query([$account2->reveal()]);

        $this->assertSame(
            [$query1],
            (new Query([$query1, $query2]))->filterType(Query::CLASS)->whereAccount('1')->asArray(),
            'transB should be removed as it does not contain account 1'
        );
    }

    /**
     * @depends testWhere
     */
    public function testWhereAmountEquals()
    {
        $transA = $this->prophesize(TransactionInterface::CLASS);
        $transA->getAmount()->willReturn(new Amount('4'));
        $transA->select()->willReturn(new Query());
        $transA = $transA->reveal();

        $transB = $this->prophesize(TransactionInterface::CLASS);
        $transB->getAmount()->willReturn(new Amount('2'));
        $transB->select()->willReturn(new Query());
        $transB = $transB->reveal();

        $verA = $this->prophesize(VerificationInterface::CLASS);
        $verA->getMagnitude()->willReturn(new Amount('3'));
        $verA->select()->willReturn(new Query());
        $verA = $verA->reveal();

        $verB = $this->prophesize(VerificationInterface::CLASS);
        $verB->getMagnitude()->willReturn(new Amount('1'));
        $verB->select()->willReturn(new Query());
        $verB = $verB->reveal();

        $testItems = [$transA, $transB, $verA, $verB];

        $this->assertSame(
            [$transA],
            (new Query($testItems))->whereAmountEquals(new Amount('4'))->asArray()
        );

        $this->assertSame(
            [$verA],
            (new Query($testItems))->whereAmountEquals(new Amount('3'))->asArray()
        );

        return $testItems;
    }

    /**
     * @depends testWhereAmountEquals
     */
    public function testWhereAmountIsGreaterThan(array $testItems)
    {
        $this->assertCount(
            2,
            (new Query($testItems))->whereAmountIsGreaterThan(new Amount('2'))->asArray()
        );

        $this->assertCount(
            1,
            (new Query($testItems))->whereAmountIsGreaterThan(new Amount('3'))->asArray()
        );
    }

    /**
     * @depends testWhereAmountEquals
     */
    public function testWhereAmountIsLessThan(array $testItems)
    {
        $this->assertCount(
            1,
            (new Query($testItems))->whereAmountIsLessThan(new Amount('2'))->asArray()
        );

        $this->assertCount(
            2,
            (new Query($testItems))->whereAmountIsLessThan(new Amount('3'))->asArray()
        );
    }

    public function testGetAccount()
    {
        $account = $this->prophesize(AccountInterface::CLASS);
        $account->getId()->willReturn('1234');
        $account = $account->reveal();

        $this->assertEquals(
            $account,
            (new Query(['foo', $account, 'bar']))->getAccount('1234')
        );
    }

    public function testExceptionOnUnknownAccountNumber()
    {
        $this->expectException(Exception\RuntimeException::CLASS);

        $dimension = $this->prophesize(DimensionInterface::CLASS);
        $dimension->getId()->willReturn('1234');
        $dimension->select()->willReturn(new Query());

        (new Query([$dimension->reveal()]))->getAccount('1234');
    }

    public function testGetDimension()
    {
        $dimension = $this->prophesize(DimensionInterface::CLASS);
        $dimension->getId()->willReturn('1234');
        $dimension->select()->willReturn(new Query());
        $dimension = $dimension->reveal();

        $this->assertEquals(
            $dimension,
            (new Query(['foo', $dimension, 'bar']))->getDimension('1234')
        );
    }

    public function testExceptionOnUnknownDimensionNumber()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        (new Query())->getDimension('1234');
    }

    /**
     * @depends testAsArray
     */
    public function testLimit()
    {
        $query = new Query([1, 2, 3, 4]);

        $this->assertEquals(
            [1, 2],
            $query->limit(2)->asArray()
        );

        $this->assertEquals(
            [1, 2, 3, 4],
            $query->limit(10)->asArray()
        );

        $this->assertEquals(
            [2, 3],
            $query->limit(2, 1)->asArray()
        );

        $this->assertEquals(
            [3, 4],
            $query->limit(100, 2)->asArray()
        );
    }

    /**
     * @depends testAsArray
     */
    public function testLoad()
    {
        $this->assertSame(
            [1, 2, 3, 4],
            (new Query([1, 2]))->load([3, 4])->asArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testMacro()
    {
        Query::macro('whereInternalType', function ($type) {
            return $this->filter(function ($item) use ($type) {
                return gettype($item) == $type;
            });
        });

        $this->assertSame(
            ['A'],
            (new Query([1, 'A', false]))->whereInternalType('string')->asArray()
        );
    }

    public function testExceptionWhenOverwritingMethodWithMacro()
    {
        $this->expectException(Exception\LogicException::CLASS);
        Query::macro('filter', function () {
        });
    }

    public function testExceptionWhenOverwritingMacro()
    {
        $this->expectException(Exception\LogicException::CLASS);
        Query::macro('thisRareMacroNameIsCreated', function () {
        });
        Query::macro('thisRareMacroNameIsCreated', function () {
        });
    }

    public function testExceptionOnUndefinedMethodCall()
    {
        $this->expectException(Exception\LogicException::CLASS);
        (new Query())->thisMethodDoesNotExist();
    }
}
