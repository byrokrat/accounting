<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use Prophecy\Argument;
use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getAccountMock(int $number = 0, string $name = '', bool $equals = false): Account
    {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getName()->willReturn($name);
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
        $verification->getAccounts()->willReturn(new AccountSet(...$accounts));
        $verification->getTransactions()->willReturn($transactions);
        $verification->query()->will(function () use ($transactions) {
            return new Query($transactions);
        });

        return $verification->reveal();
    }
}
