<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Exception\RuntimeException;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\AccountBuilder
 */
class AccountBuilderTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testCreateAccount()
    {
        $logger = $this->prophesize(Logger::class);

        $accountBuilder = new AccountBuilder($logger->reveal());

        $accountBuilder->addAccount('1234', 'foobar');

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame('1234', $account->getId());
        $this->assertSame('foobar', $account->getDescription());
        $this->assertEquals(['1234' => $account], $accountBuilder->getAccounts());
    }

    public function testCreateUnspecifiedAccount()
    {
        $logger = $this->prophesize(Logger::class);

        $accountBuilder = new AccountBuilder($logger->reveal());

        $account = $accountBuilder->getAccount('1234');

        $this->assertSame('UNSPECIFIED', $account->getDescription());
    }

    public function testWarningOnFailureCreatingAccount()
    {
        $logger = $this->prophesize(Logger::class);

        $logger->log('warning', Argument::any())->shouldBeCalled();

        $accountBuilder = new AccountBuilder($logger->reveal());

        $accountBuilder->addAccount('this-is-not-numerical', '');
    }

    public function testExceptionOnFailureGettingAccount()
    {
        $logger = $this->prophesize(Logger::class);

        $accountBuilder = new AccountBuilder($logger->reveal());

        $this->expectException(RuntimeException::class);

        $accountBuilder->getAccount('this-is-not-numerical');
    }

    public function testSetAccountType()
    {
        $logger = $this->prophesize(Logger::class);

        $accountBuilder = new AccountBuilder($logger->reveal());

        $accountBuilder->addAccount('1234', '');

        $originalAccount = $accountBuilder->getAccount('1234');

        $this->assertFalse($originalAccount->isDebtAccount());

        $accountBuilder->setAccountType('1234', 'S');

        $editedAccount = $accountBuilder->getAccount('1234');

        $this->assertTrue($editedAccount->isDebtAccount());
    }

    public function testSetUnvalidAccountType()
    {
        $logger = $this->prophesize(Logger::class);

        $logger->log('warning', Argument::any())->shouldBeCalled();

        $accountBuilder = new AccountBuilder($logger->reveal());

        $accountBuilder->setAccountType('1234', 'not-a-valid-account-type-identifier');
    }
}
