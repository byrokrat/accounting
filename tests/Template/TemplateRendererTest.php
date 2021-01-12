<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\AccountingDate;
use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\MoneyFactory;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Query;
use byrokrat\accounting\Verification\VerificationInterface;
use Money\Money;
use Prophecy\Argument;

/**
 * @covers \byrokrat\accounting\Template\TemplateRenderer
 */
class TemplateRendererTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testRenderVerification()
    {
        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $this->createMock(MoneyFactory::class),
        );

        $this->assertInstanceOf(
            VerificationInterface::class,
            $renderer->render(new VerificationTemplate(), new Translator([]))
        );
    }

    public function testTranslatorCalled()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->translate(Argument::any())->willReturn('');

        $translator->translate('foobar')->willReturn('1')->shouldBeCalled();

        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $this->createMock(MoneyFactory::class),
        );

        $renderer->render(
            new VerificationTemplate(id: 'foobar'),
            $translator->reveal()
        );
    }

    public function testVerificationValues()
    {
        $template = new VerificationTemplate(
            id: '666',
            transactionDate: '20200101',
            registrationDate: '20201231',
            description: 'desc',
            signature: 'sign',
            attributes: [new AttributeTemplate('foo', 'bar')],
        );

        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $this->createMock(MoneyFactory::class),
        );

        $verification = $renderer->render($template, new Translator([]));

        $this->assertSame('666', $verification->getId());
        $this->assertEquals(AccountingDate::fromString('20200101'), $verification->getTransactionDate());
        $this->assertEquals(AccountingDate::fromString('20201231'), $verification->getRegistrationDate());
        $this->assertSame('desc', $verification->getDescription());
        $this->assertSame('sign', $verification->getSignature());
        $this->assertSame('bar', $verification->getAttribute('foo'));
    }

    public function testRenderTransaction()
    {
        $moneyFactory = $this->prophesize(MoneyFactory::class);
        $moneyFactory->createMoney(Argument::any())->willReturn(Money::SEK('0'));

        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $moneyFactory->reveal(),
        );

        $template = new VerificationTemplate(transactions: [new TransactionTemplate()]);

        list($transaction) = $renderer->render($template, new Translator([]))->getTransactions();

        $this->assertInstanceOf(TransactionInterface::class, $transaction);
    }

    public function testTransactionValues()
    {
        $dimension = $this->createMock(DimensionInterface::class);
        $account = $this->createMock(AccountInterface::class);

        $query = $this->prophesize(Query::class);
        $query->dimension('dim')->willReturn($dimension)->shouldBeCalled();
        $query->account('1234')->willReturn($account)->shouldBeCalled();

        $amount = Money::SEK('0');

        $moneyFactory = $this->prophesize(MoneyFactory::class);
        $moneyFactory->createMoney('0')->willReturn($amount)->shouldBeCalled();

        $renderer = new TemplateRenderer($query->reveal(), $moneyFactory->reveal());

        $template = new VerificationTemplate(
            id: '666',
            transactions: [
                new TransactionTemplate(
                    transactionDate: '19900102',
                    description: 'desc',
                    signature: 'sign',
                    amount: '0',
                    account: '1234',
                    dimensions: ['dim'],
                    attributes: [new AttributeTemplate('foo', 'bar')],
                    added: '1',
                    deleted: '',
                )
            ]
        );

        list($transaction) = $renderer->render($template, new Translator([]))->getTransactions();

        $this->assertSame('666', $transaction->getVerificationId());
        $this->assertEquals(AccountingDate::fromString('19900102'), $transaction->getTransactionDate());
        $this->assertSame('desc', $transaction->getDescription());
        $this->assertSame('sign', $transaction->getSignature());
        $this->assertSame($amount, $transaction->getAmount());
        $this->assertSame($account, $transaction->getAccount());
        $this->assertSame([$dimension], $transaction->getDimensions());
        $this->assertSame('bar', $transaction->getAttribute('foo'));
        $this->assertTrue($transaction->isAdded());
        $this->assertFalse($transaction->isDeleted());
    }

    public function testTransactionDefaultsFromVerification()
    {
        $account = $this->createMock(AccountInterface::class);
        $query = $this->prophesize(Query::class);
        $query->account(Argument::any())->willReturn($account);

        $amount = Money::SEK('0');
        $moneyFactory = $this->prophesize(MoneyFactory::class);
        $moneyFactory->createMoney(Argument::any())->willReturn($amount);

        $renderer = new TemplateRenderer($query->reveal(), $moneyFactory->reveal());

        $template = new VerificationTemplate(
            id: '666',
            transactionDate: '20200101',
            description: 'desc',
            signature: 'sign',
            transactions: [new TransactionTemplate()]
        );

        list($transaction) = $renderer->render($template, new Translator([]))->getTransactions();

        $this->assertEquals(AccountingDate::fromString('20200101'), $transaction->getTransactionDate());
        $this->assertSame('desc', $transaction->getDescription());
        $this->assertSame('sign', $transaction->getSignature());
    }
}
