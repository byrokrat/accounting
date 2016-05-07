<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class TransactionProcessorTest extends BaseTestCase
{
    public function testCallbacks()
    {
        $processor = new TransactionProcessor;

        $sum = new Amount('0');

        $processor->onAccount($this->getAccountMock(1920), function (Transaction $transaction) use (&$sum) {
            $sum = $sum->add($transaction->getAmount());
        });

        $processor->process(
            new Journal(
                $this->getVerificationMock([], [
                    $this->getTransactionMock(
                        new Amount('100'),
                        $this->getAccountMock(1920)
                    ),
                    $this->getTransactionMock(
                        new Amount('-100'),
                        $this->getAccountMock(3000)
                    )
                ]),
                $this->getVerificationMock([], [
                    $this->getTransactionMock(
                        new Amount('100'),
                        $this->getAccountMock(1920)
                    ),
                    $this->getTransactionMock(
                        new Amount('-100'),
                        $this->getAccountMock(3000)
                    )
                ])
            )
        );

        $this->assertEquals(
            new Amount('200'),
            $sum,
            'The 1920 callback should fire 2 times resulting in the sum of 200'
        );
    }
}
