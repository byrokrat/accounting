<?php
declare(strict_types=1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getAccountMock(int $number = 0, string $name = '')
    {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getName()->willReturn($name);

        return $account->reveal();
    }

    protected function getTransactionMock(Amount $amount = null, Account $account = null)
    {
        $transaction = $this->prophesize(Transaction::CLASS);
        $transaction->getAmount()->willReturn($amount ?: $this->prophesize(Amount::CLASS)->reveal());
        $transaction->getAccount()->willReturn($account ?: $this->prophesize(Account::CLASS)->reveal());

        return $transaction->reveal();
    }

    protected function getVerificationMock(array $accounts = [])
    {
        $verification = $this->prophesize(Verification::CLASS);
        $verification->isBalanced()->willReturn(true);
        $verification->getAccounts()->willReturn(new AccountSet(...$accounts));

        return $verification->reveal();
    }
}
