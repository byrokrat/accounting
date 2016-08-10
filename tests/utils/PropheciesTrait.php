<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Account;
use byrokrat\accounting\AccountFactory;
use byrokrat\accounting\Interfaces;
use byrokrat\accounting\Query;
use byrokrat\accounting\Transaction;
use byrokrat\accounting\Verification;
use byrokrat\amount\Amount;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

trait PropheciesTrait
{
    abstract public function prophesize($classOrInterface = null);

    /**
     * Create an AccountFactory prophecy
     *
     * @param  array &$accounts Store of created account prophecies
     */
    public function prophesizeAccountFactory(array &$accounts = []): ObjectProphecy
    {
        $factory = $this->prophesize(AccountFactory::CLASS);

        $that = $this;
        $factory->createAccount(Argument::any(), Argument::any())->will(function ($args) use ($that, &$accounts) {
            $accounts[$args[0]] = $that->prophesizeAccount($args[0], $args[1]);
            return $accounts[$args[0]]->reveal();
        });

        return $factory;
    }

    /**
     * Create an Account prophecy
     *
     * @param  integer $number Will be returned by getNumber()
     * @param  string  $desc   Will be returned by getDescription()
     * @param  bool    $equals Will be returned by equals()
     * @param  array   $attr   Will be returned by getAttributes()
     */
    public function prophesizeAccount(
        int $number = 0,
        string $desc = '',
        bool $equals = false,
        array $attr = []
    ): ObjectProphecy {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getDescription()->willReturn($desc);
        $account->equals(Argument::any())->willReturn($equals);
        $account->getAttributes()->willReturn($attr);

        return $account;
    }

    /**
     * Create amount prophecy
     */
    public function prophesizeAmount(): ObjectProphecy
    {
        return $this->prophesize(Amount::CLASS);
    }

    /**
     * Create queryable prophecy
     *
     * @param array $content Will be returned as query content by query()
     */
    public function prophesizeQueryable(array $content = []): ObjectProphecy
    {
        $queryable = $this->prophesize(Interfaces\Queryable::CLASS);
        $queryable->query()->will(function () use ($content) {
            return new Query($content);
        });

        return $queryable;
    }

    /**
     * Create transaction prophecy
     *
     * @param  Amount  $amount  Will be returned by getAmount()
     * @param  Account $account Will be returned by getAccount()
     */
    public function prophesizeTransaction(Amount $amount = null, Account $account = null): ObjectProphecy
    {
        $transaction = $this->prophesize(Transaction::CLASS);
        $transaction->getAmount()->willReturn($amount ?: new Amount('0'));
        $transaction->getAccount()->willReturn($account ?: $this->prophesizeAccount()->reveal());
        $transaction->query()->will(function () use ($amount, $account) {
            return new Query([$amount, $account]);
        });

        return $transaction;
    }

    /**
     * Create verification prophecy
     *
     * @param  array $transactions Will be returned by getTransactions() and as query() content
     */
    public function prophesizeVerification(array $transactions = []): ObjectProphecy
    {
        $verification = $this->prophesize(Verification::CLASS);
        $verification->isBalanced()->willReturn(true);
        $verification->getTransactions()->willReturn($transactions);
        $verification->query()->will(function () use ($transactions) {
            return new Query($transactions);
        });

        return $verification;
    }
}
