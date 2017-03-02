<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class QueryTest extends \PHPUnit\Framework\TestCase
{
    use utils\PropheciesTrait;

    public function testInvalidConstructorArgument()
    {
        $this->expectException(Exception\LogicException::CLASS);
        (new Query(0))->exec();
    }

    public function testToArray()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, 2, 3]))->toArray()
        );
    }

    public function testToContainer()
    {
        $this->assertEquals(
            new Container(1, 2, 3),
            (new Query([1, 2, 3]))->toContainer()
        );
    }

    public function testToTransactionSummary()
    {
        $trans = $this->prophesizeTransaction(new Amount('50'))->reveal();

        $this->assertEquals(
            new Amount('100'),
            (new Query([1, $trans, $trans]))->toTransactionSummary()->getOutgoingBalance()
        );
    }

    /**
     * @depends testToArray
     */
    public function testNestedIteration()
    {
        $queryable1 = $this->prophesizeQueryable(['bar'])->reveal();
        $queryable2 = $this->prophesizeQueryable(['foo', $queryable1])->reveal();

        $query = new Query(['before', $queryable2, 'after']);

        $this->assertSame(
            ['before', $queryable2, 'foo', $queryable1, 'bar', 'after'],
            $query->toArray(),
            'Nested iteration should yield values bottom down'
        );

        $this->assertSame(
            ['before', $queryable2, 'foo', $queryable1, 'bar', 'after'],
            $query->toArray(),
            'Query should be rewindable and yield the same results the second time'
        );
    }

    /**
     * @depends testNestedIteration
     */
    public function testFilter()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, $this->prophesizeQueryable([2])->reveal(), 3]))->filter('is_integer')->toArray()
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
            $query->filter('is_integer')->toArray()
        );

        $this->assertSame(
            [1, 'A', 2],
            $query->toArray()
        );
    }

    public function testFirst()
    {
        $this->assertSame(
            1,
            (new Query([1, 2, 3]))->first()
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
            (new Query(['A', false, 3]))->filter('is_integer')->first()
        );
    }

    public function testFirstWithNoItems()
    {
        $this->assertNull((new Query)->first());
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
        $this->assertSame(
            3,
            count((new Query([1, $this->prophesizeQueryable([2])->reveal(), 3]))->filter('is_integer'))
        );
    }

    /**
     * @depends testFilter
     */
    public function testAccounts()
    {
        $this->assertSame(
            [$account = $this->prophesizeAccount()->reveal()],
            (new Query([1, $account, 3]))->accounts()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testAmounts()
    {
        $this->assertSame(
            [$amount = $this->prophesizeAmount()->reveal()],
            (new Query([1, $amount, 3]))->amounts()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testAttributables()
    {
        $attributable = $this->createMock(Interfaces\Attributable::CLASS);
        $this->assertSame(
            [$attributable],
            (new Query([1, $attributable, 3]))->attributables()->toArray()
        );
    }

    /**
     * @depends testAttributables
     */
    public function testWithAttribute()
    {
        $attributableProphecy = $this->prophesize(Interfaces\Attributable::CLASS);

        $attributableProphecy->hasAttribute('A')->willReturn(true);
        $attributableProphecy->getAttribute('A')->willReturn('foobar');
        $attributableProphecy->hasAttribute('B')->willReturn(false);

        $attributable = $attributableProphecy->reveal();

        $this->assertSame(
            [$attributable],
            (new Query([1, $attributable, 3]))->withAttribute('A')->toArray()
        );

        $this->assertSame(
            [],
            (new Query([1, $attributable, 3]))->withAttribute('B')->toArray()
        );

        $this->assertSame(
            [$attributable],
            (new Query([1, $attributable, 3]))->withAttribute('A', 'foobar')->toArray()
        );

        $this->assertSame(
            [],
            (new Query([1, $attributable, 3]))->withAttribute('A', 'not-foobar')->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testDateables()
    {
        $this->assertSame(
            [$dateable = $this->prophesize(Interfaces\Dateable::CLASS)->reveal()],
            (new Query([1, $dateable, 3]))->dateables()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testDescribables()
    {
        $this->assertSame(
            [$describable = $this->prophesize(Interfaces\Describable::CLASS)->reveal()],
            (new Query([1, $describable, 3]))->describables()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testDimensions()
    {
        $this->assertSame(
            [$dimension = $this->prophesizeDimension()->reveal()],
            (new Query([1, $dimension, 3]))->dimensions()->toArray()
        );
    }

    public function testQueryIsQueryable()
    {
        $this->assertSame(
            $query = new Query,
            $query->query()
        );
    }

    /**
     * @depends testFilter
     */
    public function testQueryables()
    {
        $this->assertSame(
            [$queryable = $this->prophesizeQueryable()->reveal()],
            (new Query([1, $queryable, 3]))->queryables()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testSignables()
    {
        $this->assertSame(
            [$signable = $this->prophesize(Interfaces\Signable::CLASS)->reveal()],
            (new Query([1, $signable, 3]))->signables()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testTransactions()
    {
        $this->assertSame(
            [$transaction = $this->prophesizeTransaction()->reveal()],
            (new Query([1, $transaction, 3]))->transactions()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testVerifications()
    {
        $this->assertSame(
            [$verification = $this->prophesizeVerification()->reveal()],
            (new Query([1, $verification, 3]))->verifications()->toArray()
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
     * @depends testToArray
     */
    public function testMap()
    {
        $this->assertSame(
            [10, 20],
            (new Query([0, 10]))->map(function ($integer) {
                return $integer + 10;
            })->toArray()
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
            })->toArray()
        );

        $this->assertSame(
            [0, 10],
            $query->toArray()
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
     * @depends testToArray
     */
    public function testUnique()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, 2, 3, 2]))->unique()->toArray()
        );
    }

    /**
     * @depends testUnique
     */
    public function testUniqueWithObjectItems()
    {
        $queryableA = $this->prophesizeQueryable()->reveal();
        $queryableB = $this->prophesizeQueryable()->reveal();

        $this->assertSame(
            [$queryableA, $queryableB],
            (new Query([$queryableA, $queryableB, $queryableB, $queryableA]))->unique()->toArray()
        );
    }

    /**
     * @depends testQueryables
     * @depends testToArray
     */
    public function testWhereAndWhereNot()
    {
        $queryableA = $this->prophesizeQueryable(['A', 'foo'])->reveal();
        $queryableB = $this->prophesizeQueryable(['B', 'foo'])->reveal();
        $queryableC = $this->prophesizeQueryable(['C', 'bar'])->reveal();

        $query = new Query([$queryableA, $queryableB, $queryableC]);

        $filter = function ($item) {
            return is_string($item) && $item == 'foo';
        };

        $this->assertSame(
            [$queryableA, $queryableB],
            (clone $query)->queryables()->where($filter)->toArray(),
            'queryableC should be removed as it does not contain the subitem foo'
        );

        $this->assertSame(
            [$queryableC],
            (clone $query)->queryables()->whereNot($filter)->toArray(),
            'queryableC should be kept as it does not contain the subitem foo'
        );
    }

    /**
     * @depends testWhereAndWhereNot
     */
    public function testWithAccount()
    {
        $transA = $this->prophesizeTransaction(null, $this->prophesizeAccount('1234')->reveal())->reveal();
        $transB = $this->prophesizeTransaction(null, $this->prophesizeAccount('1000')->reveal())->reveal();

        $this->assertSame(
            [$transA],
            (new Query([$transA, $transB]))->transactions()->withAccount('1234')->toArray(),
            'queryableB should be removed as it does not contain account 1234'
        );
    }

    public function testFindAccount()
    {
        $this->assertEquals(
            $account = $this->prophesizeAccount('1234', '')->reveal(),
            (new Query(['foo', $account, 'bar']))->findAccount('1234')
        );
    }

    public function testExceptionOnUnknownAccountNumber()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        (new Query)->findAccount('1234');
    }

    public function testFindDimension()
    {
        $this->assertEquals(
            $dimension = $this->prophesizeDimension('1234')->reveal(),
            (new Query(['foo', $dimension, 'bar']))->findDimension('1234')
        );
    }

    public function testExceptionOnUnknownDimensionNumber()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        (new Query)->findDimension('1234');
    }

    /**
     * @depends testToArray
     */
    public function testLoad()
    {
        $this->assertSame(
            [1, 2, 3, 4],
            (new Query([1, 2]))->load([3, 4])->toArray()
        );
    }

    public function testExceptionOnLoadingUnvalidData()
    {
        $this->expectException(Exception\LogicException::CLASS);
        (new Query)->load(null);
    }
}
