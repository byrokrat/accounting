<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Account;
use byrokrat\accounting\Exception;
use Psr\Log\LoggerInterface;

/**
 * @covers \byrokrat\accounting\Sie4\AccountBuilder
 */
class AccountBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \byrokrat\accounting\utils\PropheciesTrait;

    public function testCreateAccount()
    {
        $accountFactoryProphecy = $this->prophesizeAccountFactory();

        $accountBuilder = new AccountBuilder(
            $accountFactoryProphecy->reveal(),
            $this->createMock(LoggerInterface::CLASS)
        );

        $accountBuilder->addAccount('1234', 'foobar');
        $accountFactoryProphecy->createAccount('1234', 'foobar')->shouldHaveBeenCalled();

        $this->assertCount(1, $accountBuilder->getAccounts());
    }

    public function testWarningOnFailureCreatingAccount()
    {
        $accountFactoryProphecy = $this->prophesizeAccountFactory();

        $accountFactoryProphecy->createAccount('1234', 'foobar')->willThrow(new Exception\RuntimeException);

        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder(
            $accountFactoryProphecy->reveal(),
            $logger->reveal()
        );

        $accountBuilder->addAccount('1234', 'foobar');

        $logger->warning(\Prophecy\Argument::type('string'))->shouldHaveBeenCalled();
    }

    public function testExceptionOnFailureGettingAccount()
    {
        $accountFactoryProphecy = $this->prophesizeAccountFactory();

        $accountFactoryProphecy->createAccount('1234', 'UNSPECIFIED')->willThrow(new Exception\RuntimeException);

        $accountBuilder = new AccountBuilder(
            $accountFactoryProphecy->reveal(),
            $this->createMock(LoggerInterface::CLASS)
        );

        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $accountBuilder->getAccount('1234');
    }

    public function testGetAccount()
    {
        $accountBuilder = new AccountBuilder(
            $this->prophesizeAccountFactory()->reveal(),
            $this->createMock(LoggerInterface::CLASS)
        );

        $accountBuilder->addAccount('1234', 'foobar');

        $this->assertSame(
            $accountBuilder->getAccount('1234'),
            $accountBuilder->getAccount('1234')
        );
    }

    public function testGetUnspecifiedAccount()
    {
        $accountBuilder = new AccountBuilder(
            $this->prophesizeAccountFactory()->reveal(),
            $this->createMock(LoggerInterface::CLASS)
        );

        $this->assertSame(
            'UNSPECIFIED',
            $accountBuilder->getAccount('1234')->getDescription()
        );
    }

    public function testSetAccountType()
    {
        $accountProphecies = [];

        $accountBuilder = new AccountBuilder(
            $this->prophesizeAccountFactory($accountProphecies)->reveal(),
            $this->createMock(LoggerInterface::CLASS)
        );

        $originalAccount = $accountBuilder->getAccount('1234');

        $accountBuilder->setAccountType('1234', 'S');

        $newAccount = $accountBuilder->getAccount('1234');

        $this->assertNotSame(
            $originalAccount,
            $newAccount
        );

        $this->assertNotInstanceOf(
            Account\Debt::CLASS,
            $originalAccount
        );

        $this->assertInstanceOf(
            Account\Debt::CLASS,
            $newAccount
        );

        $this->assertSame(
            $originalAccount->getNumber(),
            $newAccount->getNumber()
        );

        $this->assertSame(
            $originalAccount->getDescription(),
            $newAccount->getDescription()
        );

        $accountProphecies['1234']->getAttributes()->shouldHaveBeenCalled();

        $this->assertSame(
            $originalAccount->getAttributes(),
            $newAccount->getAttributes()
        );

        $this->assertSame(
            $newAccount,
            $accountBuilder->getAccount('1234')
        );
    }

    public function testSetUnvalidAccountType()
    {
        $logger = $this->prophesize(LoggerInterface::CLASS);

        $accountBuilder = new AccountBuilder(
            $this->prophesizeAccountFactory()->reveal(),
            $logger->reveal()
        );

        $originalAccount = $accountBuilder->getAccount('1234');
        $accountBuilder->setAccountType('1234', 'not-a-valid-account-type-identifier');
        $newAccount = $accountBuilder->getAccount('1234');

        $logger->warning('Account number 1234 not defined', ["_addToLineCount" => 1])->shouldHaveBeenCalled();

        $this->assertSame(
            $originalAccount,
            $newAccount
        );
    }
}
