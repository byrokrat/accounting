<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use Prophecy\Argument;
use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getAccountMock(int $number = 0, string $name = '', bool $equals = false)
    {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getName()->willReturn($name);
        $account->equals(Argument::any())->willReturn($equals);

        return $account->reveal();
    }

    protected function getAmountMock()
    {
        return $this->prophesize(Amount::CLASS)->reveal();
    }

    protected function getQueryableMock(array $content = [])
    {
        $queryable = $this->prophesize(Queryable::CLASS);
        $queryable->getQueryableContent()->will(function () use ($content) {
            foreach ($content as $item) {
                yield $item;
            }
        });

        return $queryable->reveal();
    }

    protected function getTransactionMock(Amount $amount = null, Account $account = null)
    {
        $transaction = $this->prophesize(Transaction::CLASS);
        $transaction->getAmount()->willReturn($amount ?: $this->getAmountMock());
        $transaction->getAccount()->willReturn($account ?: $this->getAccountMock());

        return $transaction->reveal();
    }

    protected function getVerificationMock(array $accounts = [], array $transactions = [])
    {
        $verification = $this->prophesize(Verification::CLASS);
        $verification->isBalanced()->willReturn(true);
        $verification->getAccounts()->willReturn(new AccountSet(...$accounts));
        $verification->getTransactions()->willReturn(new TransactionSet(...$transactions));

        return $verification->reveal();
    }
}
