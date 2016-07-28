<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use Prophecy\Argument;
use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getAccountMock(int $number = 0, string $description = '', bool $equals = false): Account
    {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getDescription()->willReturn($description);
        $account->equals(Argument::any())->willReturn($equals);

        return $account->reveal();
    }

    protected function getAmountMock(): Amount
    {
        return $this->prophesize(Amount::CLASS)->reveal();
    }

    protected function getQueryableMock(array $content = []): Queryable
    {
        $queryable = $this->prophesize(Queryable::CLASS);
        $queryable->query()->will(function () use ($content) {
            return new Query($content);
        });

        return $queryable->reveal();
    }

    protected function getTransactionMock(Amount $amount = null, Account $account = null): Transaction
    {
        $transaction = $this->prophesize(Transaction::CLASS);
        $transaction->getAmount()->willReturn($amount ?: new Amount('0'));
        $transaction->getAccount()->willReturn($account ?: $this->getAccountMock());
        $transaction->query()->will(function () use ($amount, $account) {
            return new Query([$amount, $account]);
        });

        return $transaction->reveal();
    }

    protected function getVerificationMock(array $accounts = [], array $transactions = []): Verification
    {
        $verification = $this->prophesize(Verification::CLASS);
        $verification->isBalanced()->willReturn(true);
        $verification->getTransactions()->willReturn($transactions);
        $verification->query()->will(function () use ($transactions) {
            return new Query($transactions);
        });

        return $verification->reveal();
    }

    protected function assertAttributable($attributable)
    {
        $this->assertSame(
            '',
            $attributable->getAttribute('FOO'),
            'When not set reading an attribute should return the empty string'
        );

        $attributable->setAttribute('FOO', 'bar');

        $this->assertSame(
            'bar',
            $attributable->getAttribute('FOO'),
            'Getting a set attribute should return it'
        );

        $this->assertSame(
            'bar',
            $attributable->getAttribute('foO'),
            'Getting a set attribute should base case-insensitive'
        );

        $this->assertSame(
            ['foo' => 'bar'],
            $attributable->getAttributes(),
            'Getting all attributes should return attribute i small case'
        );
    }
}
