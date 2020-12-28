<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\QueryableInterface;
use byrokrat\accounting\Query;
use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\amount\Amount;

/**
 * @covers \byrokrat\accounting\Template\TemplateRenderer
 */
class TemplateRendererTest extends \PHPUnit\Framework\TestCase
{
    public function testTranslations()
    {
        $template = $this->prophesize(VerificationTemplate::CLASS);
        $template->getValues()->willReturn(['foo']);

        $translator = $this->prophesize(Translator::CLASS);
        $translator->translate(['foo'])->willReturn(['bar'])->shouldBeCalled();

        $renderer = new TemplateRenderer($this->createMock(QueryableInterface::CLASS));

        $renderer->render($template->reveal(), $translator->reveal());

        return [$template, $translator];
    }

    /**
     * @depends testTranslations
     */
    public function testDatesFromFactory($mocks)
    {
        list($template, $translator) = $mocks;

        $date = new \DateTimeImmutable();
        $dateFactory = $this->prophesize(DateFactory::CLASS);
        $dateFactory->createDate('foo')->willReturn($date);
        $dateFactory->createDate('')->willReturn($date);

        $dimensions = $this->createMock(QueryableInterface::CLASS);

        $renderer = new TemplateRenderer($dimensions, null, $dateFactory->reveal());

        $translator->translate(['foo'])->willReturn([
            'transaction_date' => 'foo',
        ]);

        $ver = $renderer->render($template->reveal(), $translator->reveal());

        $this->assertSame($date, $ver->getTransactionDate());
        $this->assertSame($date, $ver->getRegistrationDate());

        return [$template, $translator, $dateFactory];
    }

    /**
     * @depends testDatesFromFactory
     */
    public function testDimensionsFromQueryable($mocks)
    {
        list($template, $translator, $dateFactory) = $mocks;

        $dimension = $this->createMock(DimensionInterface::CLASS);
        $account = $this->createMock(AccountInterface::CLASS);

        $query = $this->prophesize(Query::CLASS);
        $query->getDimension('dim_nr')->willReturn($dimension);
        $query->getAccount('account_nr')->willReturn($account);
        $query->getAccount('')->willReturn($account);

        $queryable = $this->prophesize(QueryableInterface::CLASS);
        $queryable->select()->willReturn($query);

        $renderer = new TemplateRenderer($queryable->reveal(), null, $dateFactory->reveal());

        $translator->translate(['foo'])->willReturn([
            'transactions' => [
                [
                    'account' => 'account_nr',
                    'dimensions' => ['dim_nr']
                ]
            ],
        ]);

        $ver = $renderer->render($template->reveal(), $translator->reveal());

        $this->assertSame($account, $ver->getTransactions()[0]->getAccount());
        $this->assertSame($dimension, $ver->getTransactions()[0]->getDimensions()[0]);

        return [$template, $translator, $dateFactory, $queryable];
    }

    /**
     * @depends testDimensionsFromQueryable
     */
    public function testMoneyFromFactory($mocks)
    {
        list($template, $translator, $dateFactory, $queryable) = $mocks;

        $amount = new Amount('999');

        $moneyFactory = $this->prophesize(MoneyFactoryInterface::CLASS);
        $moneyFactory->createMoney('999')->willReturn($amount);

        $renderer = new TemplateRenderer($queryable->reveal(), $moneyFactory->reveal(), $dateFactory->reveal());

        $translator->translate(['foo'])->willReturn([
            'transactions' => [
                ['amount' => '999']
            ],
        ]);

        $ver = $renderer->render($template->reveal(), $translator->reveal());

        $this->assertSame($amount, $ver->getTransactions()[0]->getAmount());
    }

    public function testAttribute()
    {
        $template = new VerificationTemplate([
            'attributes' => [
                'foo' => 'bar'
            ]
        ]);

        $ver = (new TemplateRenderer(new Container()))->render($template, new Translator([]));

        $this->assertSame('bar', $ver->getAttribute('foo'));
    }
}
