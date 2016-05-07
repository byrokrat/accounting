<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class TransactionTest extends BaseTestCase
{
    public function testGetters()
    {
        $account = $this->prophesize(Account::CLASS)->reveal();
        $amount = $this->prophesize(Amount::CLASS)->reveal();
        $transaction = new Transaction($account, $amount);
        $this->assertEquals($account, $transaction->getAccount());
        $this->assertEquals($amount, $transaction->getAmount());
    }
}
