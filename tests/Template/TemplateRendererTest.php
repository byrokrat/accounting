<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Query;
use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\accounting\Verification\VerificationInterface;
use byrokrat\amount\Amount;
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
            $this->createMock(MoneyFactoryInterface::class),
            $this->createMock(DateFactory::class),
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
            $this->createMock(MoneyFactoryInterface::class),
            $this->createMock(DateFactory::class),
        );

        $renderer->render(
            new VerificationTemplate(id: 'foobar'),
            $translator->reveal()
        );
    }

    public function testVerificationValues()
    {
        $dateFactory = $this->prophesize(DateFactory::class);

        $transactionDate = new \DateTimeImmutable();
        $dateFactory->createDate('transactionDate')->willReturn($transactionDate)->shouldBeCalled();

        $registrationDate = new \DateTimeImmutable();
        $dateFactory->createDate('registrationDate')->willReturn($registrationDate)->shouldBeCalled();

        $template = new VerificationTemplate(
            id: '666',
            transactionDate: 'transactionDate',
            registrationDate: 'registrationDate',
            description: 'desc',
            signature: 'sign',
            attributes: [new AttributeTemplate('foo', 'bar')],
        );

        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $this->createMock(MoneyFactoryInterface::class),
            $dateFactory->reveal()
        );

        $verification = $renderer->render($template, new Translator([]));

        $this->assertSame('666', $verification->getId());
        $this->assertSame($transactionDate, $verification->getTransactionDate());
        $this->assertSame($registrationDate, $verification->getRegistrationDate());
        $this->assertSame('desc', $verification->getDescription());
        $this->assertSame('sign', $verification->getSignature());
        $this->assertSame('bar', $verification->getAttribute('foo'));
    }

    public function testRenderTransaction()
    {
        $moneyFactory = $this->prophesize(MoneyFactoryInterface::class);
        $moneyFactory->createMoney(Argument::any())->willReturn(new Amount('0'));

        $renderer = new TemplateRenderer(
            $this->createMock(Query::class),
            $moneyFactory->reveal(),
            $this->createMock(DateFactory::class),
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

        $amount = new Amount('0');

        $moneyFactory = $this->prophesize(MoneyFactoryInterface::class);
        $moneyFactory->createMoney('0')->willReturn($amount)->shouldBeCalled();

        $dateFactory = $this->prophesize(DateFactory::class);

        $transactionDate = new \DateTimeImmutable();
        $dateFactory->createDate('transactionDate')->willReturn($transactionDate)->shouldBeCalled();
        $dateFactory->createDate(Argument::any())->willReturn(new \DateTimeImmutable());

        $renderer = new TemplateRenderer($query->reveal(), $moneyFactory->reveal(), $dateFactory->reveal());

        $template = new VerificationTemplate(
            id: '666',
            transactions: [
                new TransactionTemplate(
                    transactionDate: 'transactionDate',
                    description: 'desc',
                    signature: 'sign',
                    amount: '0',
                    quantity: '1',
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
        $this->assertSame($transactionDate, $transaction->getTransactionDate());
        $this->assertSame('desc', $transaction->getDescription());
        $this->assertSame('sign', $transaction->getSignature());
        $this->assertSame($amount, $transaction->getAmount());
        $this->assertEquals(new Amount('1'), $transaction->getQuantity());
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

        $amount = new Amount('0');
        $moneyFactory = $this->prophesize(MoneyFactoryInterface::class);
        $moneyFactory->createMoney(Argument::any())->willReturn($amount);

        $dateFactory = $this->prophesize(DateFactory::class);
        $transactionDate = new \DateTimeImmutable();
        $dateFactory->createDate('transactionDate')->willReturn($transactionDate);
        $dateFactory->createDate(Argument::any())->willReturn(new \DateTimeImmutable());

        $renderer = new TemplateRenderer($query->reveal(), $moneyFactory->reveal(), $dateFactory->reveal());

        $template = new VerificationTemplate(
            id: '666',
            transactionDate: 'transactionDate',
            description: 'desc',
            signature: 'sign',
            transactions: [new TransactionTemplate()]
        );

        list($transaction) = $renderer->render($template, new Translator([]))->getTransactions();

        $this->assertSame($transactionDate, $transaction->getTransactionDate());
        $this->assertSame('desc', $transaction->getDescription());
        $this->assertSame('sign', $transaction->getSignature());
    }
}
