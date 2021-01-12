<?php

declare(strict_types=1);

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Exception\InvalidArgumentException;
use byrokrat\accounting\Exception\InvalidVerificationException;
use byrokrat\accounting\Exception\UnbalancedVerificationException;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\Transaction;
use Money\Money;
use Prophecy\Argument;

class VerificationTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testAttributes()
    {
        $attributable = new Verification();

        $this->assertFalse($attributable->hasAttribute('does-not-exist'));

        $this->assertSame('', $attributable->getAttribute('does-not-exist'));

        $attributable->setAttribute('foo', 'bar');

        $this->assertTrue($attributable->hasAttribute('foo'));

        $this->assertSame('bar', $attributable->getAttribute('foo'));

        $this->assertSame(['foo' => 'bar'], $attributable->getAttributes());
    }

    public function testId()
    {
        $this->assertSame(
            '1',
            (new Verification(id: '1'))->getId()
        );
    }

    public function testEmptyId()
    {
        $this->assertSame(
            '',
            (new Verification())->getId()
        );
    }

    public function testExceptionOnInvalidId()
    {
        $this->expectException(InvalidVerificationException::class);
        new Verification(id: 'this-is-not-a-numerical-string');
    }

    public function testAttributesToConstructor()
    {
        $ver = new Verification(attributes: ['key' => 'val']);
        $this->assertSame(
            'val',
            $ver->getAttribute('key')
        );
    }

    public function testExceptionOnNonTransactionArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        new Verification(transactions: ['this-is-not-a-transaction']);
    }

    public function testExceptionOnUnbalancedVerification()
    {
        $this->expectException(UnbalancedVerificationException::class);

        $trans = new Transaction(
            amount: Money::SEK('1'),
            account: $this->createMock(AccountInterface::class),
        );

        new Verification(transactions: [$trans]);
    }

    public function testAssigningDates()
    {
        $transactionDate = AccountingDate::fromString('20210101');
        $registrationDate = AccountingDate::fromString('19900101');

        $ver = new Verification(
            transactionDate: $transactionDate,
            registrationDate: $registrationDate,
        );

        $this->assertSame($transactionDate, $ver->getTransactionDate());
        $this->assertSame($registrationDate, $ver->getRegistrationDate());
    }

    public function testTransactionDefaultDate()
    {
        $this->assertSame(
            AccountingDate::today(),
            (new Verification())->getTransactionDate()
        );
    }

    public function testRegistrationDateDefaultsToTransactionDate()
    {
        $transactionDate = AccountingDate::fromString('19820323');

        $this->assertSame(
            $transactionDate,
            (new Verification(transactionDate: $transactionDate))->getRegistrationDate()
        );
    }

    public function testDescription()
    {
        $this->assertSame(
            'desc',
            (new Verification(description: 'desc'))->getDescription()
        );
    }

    public function testSignature()
    {
        $this->assertSame(
            'sign',
            (new Verification(signature: 'sign'))->getSignature()
        );
    }

    public function testAccessingTransactions()
    {
        $trans = new Transaction(
            amount: Money::SEK('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame(
            [$trans, $trans],
            (new Verification(transactions: [$trans, $trans]))->getTransactions()
        );
    }

    public function transactionArithmeticsProvider()
    {
        return [
            // magnitude        transaction amounts...
            [Money::EUR('30'),  Money::EUR('10'),  Money::EUR('20'),    Money::EUR('-30')],
            [Money::EUR('200'), Money::EUR('200'), Money::EUR('-200')],
            [Money::SEK('300'), Money::SEK('100'), Money::SEK('200'),   Money::SEK('-300')],
            [Money::SEK('200'), Money::SEK('200'), Money::SEK('-200')],
        ];
    }

    /**
     * @dataProvider transactionArithmeticsProvider
     */
    public function testTransactionArithmetics(Money $magnitude, Money ...$amounts)
    {
        $verification = new Verification(
            transactions: array_map(
                fn($amount) => new Transaction(amount: $amount, account: $this->createMock(AccountInterface::class)),
                $amounts
            )
        );

        $this->assertTrue($magnitude->equals($verification->getSummary()->getMagnitude()));
    }

    public function testExceptionOnCurrencyMissmatch()
    {
        $transEUR = new Transaction(
            amount: Money::EUR('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $transSEK = new Transaction(
            amount: Money::SEK('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->expectException(InvalidVerificationException::class);
        $verification = new Verification(transactions: [$transSEK, $transEUR]);
    }

    public function testExceptionOnCurrencyMissmatchInDeletedTransactions()
    {
        $transEUR = new Transaction(
            amount: Money::EUR('0'),
            account: $this->createMock(AccountInterface::class),
            deleted: true
        );

        $transSEK = new Transaction(
            amount: Money::SEK('0'),
            account: $this->createMock(AccountInterface::class),
            deleted: true
        );

        $this->expectException(InvalidVerificationException::class);
        $verification = new Verification(transactions: [$transEUR, $transSEK]);
    }

    public function testDeletedTransactionsDoesNotCount()
    {
        $trans = new Transaction(
            amount: Money::SEK('100'),
            account: $this->createMock(AccountInterface::class),
            deleted: true
        );

        $verification = new Verification(transactions: [$trans]);

        $this->assertTrue($verification->getSummary()->getMagnitude()->equals(Money::SEK('0')));
    }

    public function testGetItems()
    {
        $trans = new Transaction(
            amount: Money::SEK('0'),
            account: $this->createMock(AccountInterface::class),
        );

        $this->assertSame(
            [$trans, $trans],
            (new Verification(transactions: [$trans, $trans]))->getItems()
        );
    }
}
