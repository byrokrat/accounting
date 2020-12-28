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
            ['1999', 'desc', AssetAccount::CLASS],
            ['2999', 'desc', DebtAccount::CLASS],
            ['3999', 'desc', EarningAccount::CLASS],
            ['4000', 'desc', CostAccount::CLASS]
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
