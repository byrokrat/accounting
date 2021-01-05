<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Exception\InvalidArgumentException;
use Prophecy\Argument;

class VerificationTemplateTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testExceptionOnInvalidTransaction()
    {
        $this->expectException(InvalidArgumentException::class);
        new VerificationTemplate(transactions: [null]);
    }

    public function testExceptionOnInvalidAttribute()
    {
        $this->expectException(InvalidArgumentException::class);
        new VerificationTemplate(attributes: [null]);
    }

    public function testTranslateStrings()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->translate('foo')->willReturn('bar');

        $original = new VerificationTemplate(
            id: 'foo',
            transactionDate: 'foo',
            registrationDate: 'foo',
            description: 'foo',
            signature: 'foo',
        );

        $translated = $original->translate($translator->reveal());

        $this->assertSame('foo', $original->id);
        $this->assertSame('foo', $original->transactionDate);
        $this->assertSame('foo', $original->registrationDate);
        $this->assertSame('foo', $original->description);
        $this->assertSame('foo', $original->signature);

        $this->assertSame('bar', $translated->id);
        $this->assertSame('bar', $translated->transactionDate);
        $this->assertSame('bar', $translated->registrationDate);
        $this->assertSame('bar', $translated->description);
        $this->assertSame('bar', $translated->signature);
    }

    public function testTranslateTransactions()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->translate('foo')->willReturn('bar');
        $translator->translate(Argument::any())->willReturn('');

        $original = new VerificationTemplate(transactions: [new TransactionTemplate(signature: 'foo')]);

        $translated = $original->translate($translator->reveal());

        $this->assertSame('foo', $original->transactions[0]->signature);
        $this->assertSame('bar', $translated->transactions[0]->signature);
    }

    public function testTranslateAttributes()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->translate('foo')->willReturn('bar');
        $translator->translate(Argument::any())->willReturn('');

        $original = new VerificationTemplate(attributes: [new AttributeTemplate(key: 'foo')]);

        $translated = $original->translate($translator->reveal());

        $this->assertSame('foo', $original->attributes[0]->key);
        $this->assertSame('bar', $translated->attributes[0]->key);
    }
}
