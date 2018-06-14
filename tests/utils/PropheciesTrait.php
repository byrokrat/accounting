<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Dimension\AccountFactory;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Query;
use byrokrat\accounting\QueryableInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
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
     * @param string $number Will be returned by getId()
     * @param string $desc   Will be returned by getDescription()
     * @param array  $attr   Will be returned by getAttributes()
     */
    public function prophesizeAccount(
        string $number = '0',
        string $desc = '',
        array $attr = []
    ): ObjectProphecy {
        $account = $this->prophesize(AccountInterface::CLASS);
        $account->getId()->willReturn($number);
        $account->getDescription()->willReturn($desc);
        $account->getAttributes()->willReturn($attr);
        $account->select()->willReturn(new Query);
        $account->__toString()->willReturn('');

        return $account;
    }

    /**
     * Create amount prophecy
     */
    public function prophesizeAmount(): ObjectProphecy
    {
        $amount = $this->prophesize(Amount::CLASS);
        $amount->getString()->willReturn('');

        return $amount;
    }

    /**
     * Create dimension prophecy
     *
     * @param integer $number Will be returned by getId()
     */
    public function prophesizeDimension(string $number = '0'): ObjectProphecy
    {
        $dim = $this->prophesize(DimensionInterface::CLASS);
        $dim->getId()->willReturn($number);
        $dim->select()->willReturn(new Query);

        return $dim;
    }

    /**
     * Create queryable prophecy
     *
     * @param array $content Will be returned as query content by select()
     */
    public function prophesizeQueryable(array $content = []): ObjectProphecy
    {
        $queryable = $this->prophesize(QueryableInterface::CLASS);
        $queryable->select()->will(function () use ($content) {
            return new Query($content);
        });

        return $queryable;
    }

    /**
     * Create transaction prophecy
     *
     * @param  Amount           $amount  Will be returned by getAmount()
     * @param  AccountInterface $account Will be returned by getAccount()
     */
    public function prophesizeTransaction(Amount $amount = null, AccountInterface $account = null): ObjectProphecy
    {
        $account = $account ?: $this->prophesizeAccount()->reveal();
        $amount = $amount ?: new Amount('0');

        $transaction = $this->prophesize(TransactionInterface::CLASS);
        $transaction->getAmount()->willReturn($amount);
        $transaction->getAccount()->willReturn($account);
        $transaction->isDeleted()->willReturn(false);
        $transaction->select()->will(function () use ($amount, $account) {
            return new Query([$amount, $account]);
        });

        return $transaction;
    }

    /**
     * Create deleted transaction prophecy
     *
     * @param  Amount           $amount  Will be returned by getAmount()
     * @param  AccountInterface $account Will be returned by getAccount()
     */
    public function prophesizeDeletedTransaction(
        Amount $amount = null,
        AccountInterface $account = null
    ): ObjectProphecy {
        $transaction = $this->prophesizeTransaction($amount, $account);
        $transaction->isDeleted()->willReturn(true);

        return $transaction;
    }

    /**
     * Create verification prophecy
     *
     * @param  array $transactions Will be returned by getTransactions() and as select() content
     */
    public function prophesizeVerification(array $transactions = []): ObjectProphecy
    {
        $verification = $this->prophesize(Verification::CLASS);
        $verification->isBalanced()->willReturn(true);
        $verification->getTransactions()->willReturn($transactions);
        $verification->select()->will(function () use ($transactions) {
            return new Query($transactions);
        });

        return $verification;
    }
}
