<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class QueryTest extends BaseTestCase
{
    public function testInvalidConstructorArgument()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::CLASS);
        (new Query(0))->exec();
    }

    public function testToArray()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1, 2, 3]))->toArray()
        );
    }

    /**
     * @depends testToArray
     */
    public function testMultimpleConstructorArguments()
    {
        $this->assertSame(
            [1, 2, 3],
            (new Query([1], [2], [3]))->toArray()
        );
    }

    /**
     * @depends testToArray
     */
    public function testNestedIteration()
    {
        $queryable1 = $this->getQueryableMock(['bar']);
        $queryable2 = $this->getQueryableMock(['foo', $queryable1]);

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
            (new Query([1, $this->getQueryableMock([2]), 3]))->filter('is_integer')->toArray()
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
            count((new Query([1, $this->getQueryableMock([2]), 3]))->filter('is_integer'))
        );
    }

    /**
     * @depends testFilter
     */
    public function testAccounts()
    {
        $account = $this->getAccountMock();
        $this->assertSame(
            [$account],
            (new Query([1, $account, 3]))->accounts()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testAmounts()
    {
        $amount = $this->getAmountMock();
        $this->assertSame(
            [$amount],
            (new Query([1, $amount, 3]))->amounts()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testQueryables()
    {
        $queryable = $this->getQueryableMock();
        $this->assertSame(
            [$queryable],
            (new Query([1, $queryable, 3]))->queryables()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testTransactions()
    {
        $transaction = $this->getTransactionMock();
        $this->assertSame(
            [$transaction],
            (new Query([1, $transaction, 3]))->transactions()->toArray()
        );
    }

    /**
     * @depends testFilter
     */
    public function testVerifications()
    {
        $verifications = $this->getVerificationMock();
        $this->assertSame(
            [$verifications],
            (new Query([1, $verifications, 3]))->verifications()->toArray()
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

    public function testSumTransactions()
    {
        $this->assertEquals(
            new Amount('0'),
            (new Query)->sumTransactions()
        );

        $this->assertEquals(
            new Amount('10'),
            (new Query([
                $this->getTransactionMock(new Amount('5')),
                $this->getTransactionMock(new Amount('5')),
                'not a transaction'
            ]))->sumTransactions()
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
        $queryableA = $this->getQueryableMock();
        $queryableB = $this->getQueryableMock();

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
        $queryableA = $this->getQueryableMock(['A', 'foo']);
        $queryableB = $this->getQueryableMock(['B', 'foo']);
        $queryableC = $this->getQueryableMock(['C', 'bar']);

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
}
