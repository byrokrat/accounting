<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

class AttributeTemplateTest extends \PHPUnit\Framework\TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;

    public function testTranslate()
    {
        $translator = $this->prophesize(TranslatorInterface::class);
        $translator->translate('foo')->willReturn('bar');

        $original = new AttributeTemplate(key: 'foo', value: 'foo');

        $translated = $original->translate($translator->reveal());

        $this->assertSame('foo', $original->key);
        $this->assertSame('foo', $original->value);

        $this->assertSame('bar', $translated->key);
        $this->assertSame('bar', $translated->value);
    }
}
