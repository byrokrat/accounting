<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait, utils\PropheciesTrait;

    private function createTransaction(&$account = null, &$amount = null, &$quantity = null, &$dimensions = null)
    {
        return new Transaction(
            $account = $this->prophesizeAccount()->reveal(),
            $amount = $this->prophesizeAmount()->reveal(),
            $quantity = 10,
            ...$dimensions = [
                $this->prophesizeDimension()->reveal(),
                $this->prophesizeDimension()->reveal()
            ]
        );
    }

    public function testAccessingContent()
    {
        $transaction = $this->createTransaction($account, $amount, $quantity, $dimensions);

        $this->assertSame($account, $transaction->getAccount());
        $this->assertSame($amount, $transaction->getAmount());
        $this->assertSame($quantity, $transaction->getQuantity());
        $this->assertSame($dimensions, $transaction->getDimensions());
    }

    public function testAttributable()
    {
        $this->assertAttributable($this->createTransaction());
    }

    public function testDateable()
    {
        $transaction = $this->createTransaction();

        $this->assertDateableDateNotSet($transaction);

        $this->assertDateable(
            $date = new \DateTime,
            $transaction->setDate($date)
        );
    }

    public function testDescribable()
    {
        $this->assertDescribable(
            '',
            $this->createTransaction()
        );
    }

    public function testIterable()
    {
        $transaction = $this->createTransaction($account, $amount, $void, $dimensions);

        $this->assertSame(
            array_merge([$account, $amount], $dimensions),
            iterator_to_array($transaction)
        );
    }

    public function testQueryable()
    {
        $transaction = $this->createTransaction($account, $amount, $void, $dimensions);

        $this->assertSame(
            array_merge([$account, $amount], $dimensions),
            $transaction->query()->toArray()
        );
    }

    public function testSignable()
    {
        $transaction = $this->createTransaction();

        $this->assertSignableSignatureNotSet($transaction);

        $this->assertSignable(
            $signature = 'signature',
            $transaction->setSignature($signature)
        );
    }
}
