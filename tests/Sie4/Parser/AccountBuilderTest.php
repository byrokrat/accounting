<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\AccountFactory;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DebtAccount;
use byrokrat\accounting\Exception;
use Psr\Log\LoggerInterface;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\AccountBuilder
 */
class AccountBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateAccount()
    {
        $account = $this->createMock(AccountInterface::CLASS);

        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'foobar')->willReturn($account);

        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());
        $accountBuilder->addAccount('1234', 'foobar');

        $this->assertCount(1, $accountBuilder->getAccounts());
        $this->assertSame($account, $accountBuilder->getAccount('1234'));
    }

    public function testCreateUnspecifiedAccount()
    {
        $account = $this->createMock(AccountInterface::CLASS);

        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'UNSPECIFIED')->willReturn($account);

        $logger = $this->prophesize(LoggerInterface::CLASS);
        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());

        $this->assertSame($account, $accountBuilder->getAccount('1234'));
    }

    public function testWarningOnFailureCreatingAccount()
    {
        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'foobar')->willThrow(new Exception\RuntimeException);

        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());
        $accountBuilder->addAccount('1234', 'foobar');

        $logger->warning(\Prophecy\Argument::type('string'))->shouldHaveBeenCalled();
    }

    public function testExceptionOnFailureGettingAccount()
    {
        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'UNSPECIFIED')->willThrow(new Exception\RuntimeException);

        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());

        $this->expectException(Exception\RuntimeException::CLASS);
        $accountBuilder->getAccount('1234');
    }

    public function testCreateAccountOOO()
    {
        $account = $this->createMock(AccountInterface::CLASS);

        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'foobar')->willReturn($account);

        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());
        $accountBuilder->addAccount('1234', 'foobar');

        $this->assertCount(1, $accountBuilder->getAccounts());
        $this->assertSame($account, $accountBuilder->getAccount('1234'));
    }

    public function testSetAccountType()
    {
        $account = $this->prophesize(AccountInterface::CLASS);
        $account->getDescription()->willReturn('foobar');
        $account->getAttributes()->willReturn(['foo' => 'bar']);

        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $accountFactory->createAccount('1234', 'UNSPECIFIED')->willReturn($account);

        $logger = $this->prophesize(LoggerInterface::CLASS);
        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());

        // Creates the unspecified account
        $accountBuilder->getAccount('1234');

        // Set type of previously unspecified account
        $accountBuilder->setAccountType('1234', 'S');

        $newAccount = $accountBuilder->getAccount('1234');

        $this->assertInstanceOf(DebtAccount::CLASS, $newAccount);
        $this->assertSame('1234', $newAccount->getId());
        $this->assertSame('foobar', $newAccount->getDescription());
        $this->assertSame(['foo' => 'bar'], $newAccount->getAttributes());
    }

    public function testSetUnvalidAccountType()
    {
        $accountFactory = $this->prophesize(AccountFactory::CLASS);
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder($accountFactory->reveal(), $logger->reveal());

        $accountBuilder->setAccountType('1234', 'not-a-valid-account-type-identifier');
        $logger->warning(Argument::any())->shouldHaveBeenCalled();
    }
}
