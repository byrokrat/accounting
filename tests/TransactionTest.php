<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

/**
 * @covers \byrokrat\accounting\Transaction
 */
class TransactionTest extends BaseTestCase
{
    public function testGetters()
    {
        $account = $this->getAccountMock();
        $amount = $this->getAmountMock();

        $transaction = new Transaction($account, $amount);

        $this->assertEquals($account, $transaction->getAccount());
        $this->assertEquals($amount, $transaction->getAmount());

        $this->assertEquals(
            [$account, $amount],
            ($transaction)->query()->toArray()
        );
    }

    public function testAttributes()
    {
        $this->assertAttributable(
            new Transaction($this->getAccountMock(), $this->getAmountMock())
        );
    }
}
