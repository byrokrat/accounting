<?php
declare(strict_types=1);

namespace byrokrat\accounting;

class AccountSetTest extends \PHPUnit_Framework_TestCase
{
    private function createAccount(int $number = 0, string $name = '')
    {
        $account = $this->prophesize(Account::CLASS);
        $account->getNumber()->willReturn($number);
        $account->getName()->willReturn($name);

        return $account->reveal();
    }

    public function testAddAndReadAccounts()
    {
        $accounts = [
            $this->createAccount(0),
            $this->createAccount(1),
        ];
        $set = new AccountSet(...$accounts);
        $this->assertEquals($accounts, $set->getAccounts());
    }

    public function testContainsAccount()
    {
        $set = new AccountSet($this->createAccount(1234));

        $this->assertTrue(
            $set->contains(1234),
            'Set should contain account 1234'
        );

        $this->assertFalse(
            $set->contains(9999),
            'Set should NOT contain account 9999'
        );
    }

    public function testGetAccount()
    {
        $account = $this->createAccount(1234, 'foobar');
        $this->assertEquals(
            $account,
            (new AccountSet($account))->getAccount(1234)
        );
    }

    public function testExceptionOnUnknownAccount()
    {
        $this->setExpectedException(Exception\OutOfBoundsException::CLASS);
        (new AccountSet)->getAccount(1234);
    }

    public function testRemoveAccount()
    {
        $set = new AccountSet($this->createAccount(1234));

        $this->assertTrue(
            $set->contains(1234),
            'Set should contain account 1234'
        );

        $set->removeAccount(1234);

        $this->assertFalse(
            $set->contains(1234),
            'Set should NOT contain account 1234 as it has been removed'
        );

        $this->assertNull(
            $set->removeAccount(1234),
            'Removing unexisting accounts should do no harm'
        );
    }

    public function testGetAccountFromName()
    {
        $account = $this->createAccount(1234, 'foobar');
        $this->assertEquals(
            $account,
            (new AccountSet($account))->getAccountFromName('foobar')
        );
    }

    public function testExceptionOnUnknownAccountName()
    {
        $this->setExpectedException(Exception\OutOfBoundsException::CLASS);
        (new AccountSet)->getAccountFromName('foobar');
    }

    public function testAlterAccount()
    {
        $set = new AccountSet($this->createAccount(1234, 'foobar'));
        $set->addAccount($this->createAccount(1234, 'altered'));

        $this->assertSame(
            'altered',
            $set->getAccount(1234)->getName(),
            'Adding multiple accounts with the same number should overwrite previous values'
        );
    }
}
