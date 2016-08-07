<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class AccountFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountTypes()
    {
        $factory = new AccountFactory;

        $this->assertInstanceOf(
            Account\Asset::CLASS,
            $factory->createAccount(1999, '')
        );

        $this->assertInstanceOf(
            Account\Debt::CLASS,
            $factory->createAccount(2999, '')
        );

        $this->assertInstanceOf(
            Account\Earning::CLASS,
            $factory->createAccount(3999, '')
        );

        $this->assertInstanceOf(
            Account\Cost::CLASS,
            $factory->createAccount(4000, '')
        );
    }
}
