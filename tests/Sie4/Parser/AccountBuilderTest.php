<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Exception\InvalidAccountException;
use byrokrat\accounting\Exception\InvalidSieFileException;
use byrokrat\accounting\Transaction\TransactionInterface;
use Money\Money;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\AccountBuilder
 */
class AccountBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testDefineAccount()
    {
        $accountBuilder = new AccountBuilder();

        $accountBuilder->defineAccount(id: '1234', description: 'foobar');

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame('1234', $account->getId());
        $this->assertSame('foobar', $account->getDescription());
    }

    /**
     * @depends testDefineAccount
     */
    public function testMultipleAccountDefinitions()
    {
        $accountBuilder = new AccountBuilder();

        $accountBuilder->defineAccount(id: '1234', description: 'desc');

        $originalAccount = $accountBuilder->getAccount('1234');

        $this->assertFalse($originalAccount->isDebtAccount());

        $accountBuilder->defineAccount(id: '1234', type: 'S');

        $editedAccount = $accountBuilder->getAccount('1234');

        $this->assertTrue($editedAccount->isDebtAccount());
    }

    public function testDefineIncomingBalance()
    {
        $accountBuilder = new AccountBuilder();

        $money = Money::SEK('100');

        $accountBuilder->defineAccount(id: '1234', incomingBalance: $money);

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame($money, $account->getSummary()->getIncomingBalance());
    }

    /**
     * @depends testDefineIncomingBalance
     */
    public function testIncomingBalanceIsPassedDuringDefine()
    {
        $accountBuilder = new AccountBuilder();

        $money = Money::SEK('100');

        $accountBuilder->defineAccount(id: '1234', incomingBalance: $money);

        $accountBuilder->defineAccount(id: '1234', description: 'desc');

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame($money, $account->getSummary()->getIncomingBalance());
    }

    public function testDefineAttributes()
    {
        $accountBuilder = new AccountBuilder();

        $accountBuilder->defineAccount(id: '1234', attributes: ['foo' => 'foo']);

        $accountBuilder->defineAccount(id: '1234', attributes: ['bar' => 'bar']);

        $accountBuilder->defineAccount(id: '1234', description: 'desc');

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame(['foo' => 'foo', 'bar' => 'bar'], $account->getAttributes());
    }

    public function testGetUnspecifiedAccount()
    {
        $accountBuilder = new AccountBuilder();

        $this->assertInstanceOf(
            AccountInterface::class,
            $accountBuilder->getAccount('1234')
        );
    }

    /**
     * @depends testGetUnspecifiedAccount
     */
    public function testGetAccounts()
    {
        $accountBuilder = new AccountBuilder();

        $accounts = [
            $accountBuilder->getAccount('1'),
            $accountBuilder->getAccount('2'),
        ];

        $this->assertSame($accounts, $accountBuilder->getAccounts());
    }

    public function testExceptionOnFailureCreatingAccount()
    {
        $accountBuilder = new AccountBuilder();

        $this->expectException(InvalidAccountException::class);

        $accountBuilder->defineAccount('this-is-not-numerical');
    }

    public function testExceptionOnInvalidAccountType()
    {
        $accountBuilder = new AccountBuilder();

        $this->expectException(InvalidAccountException::class);

        $accountBuilder->defineAccount(id: '1234', type: 'not-a-valid-account-type-identifier');
    }

    /**
     * @depends testGetUnspecifiedAccount
     * @depends testDefineAccount
     */
    public function testExceptionOnAddingDefinitionToAccountWithTransactions()
    {
        $accountBuilder = new AccountBuilder();

        $account = $accountBuilder->getAccount('1');

        $account->addTransaction($this->createMock(TransactionInterface::class));

        $this->expectException(InvalidSieFileException::class);

        $accountBuilder->defineAccount('1');
    }
}
