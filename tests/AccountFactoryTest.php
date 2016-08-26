<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

/**
 * @covers \byrokrat\accounting\AccountFactory
 */
class AccountFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function accountTypesProvider()
    {
        return [
            ['1999', 'desc', Account\Asset::CLASS],
            ['2999', 'desc', Account\Debt::CLASS],
            ['3999', 'desc', Account\Earning::CLASS],
            ['4000', 'desc', Account\Cost::CLASS]
        ];
    }

    /**
     * @dataProvider accountTypesProvider
     */
    public function testAccountTypes(string $number, string $desc, string $expectedClass)
    {
        $account = (new AccountFactory)->createAccount($number, $desc);

        $this->assertInstanceOf(
            $expectedClass,
            $account
        );

        $this->assertSame(
            $number,
            $account->getId()
        );

        $this->assertSame(
            $desc,
            $account->getDescription()
        );
    }
}
