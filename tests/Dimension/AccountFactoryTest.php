<?php

declare(strict_types=1);

namespace byrokrat\accounting\Dimension;

/**
 * @covers \byrokrat\accounting\Dimension\AccountFactory
 */
class AccountFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function accountTypesProvider()
    {
        return [
            ['1999', 'desc', AssetAccount::class],
            ['2999', 'desc', DebtAccount::class],
            ['3999', 'desc', EarningAccount::class],
            ['4000', 'desc', CostAccount::class]
        ];
    }

    /**
     * @dataProvider accountTypesProvider
     */
    public function testAccountTypes(string $number, string $desc, string $expectedClass)
    {
        $account = (new AccountFactory())->createAccount($number, $desc);

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

    public function testMultipleAccounts()
    {
        $accounts = (new AccountFactory())->createAccounts([
            '1000' => 'foo',
            '2000' => 'bar'
        ]);

        $this->assertCount(2, $accounts->select());
    }
}
