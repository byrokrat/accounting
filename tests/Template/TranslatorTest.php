<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Exception\InvalidArgumentException;

class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionOnInvalidPlaceholder()
    {
        $this->expectException(InvalidArgumentException::class);
        new Translator([123 => 'placeholder-is-not-string']);
    }

    public function testExceptionOnInvalidReplacement()
    {
        $this->expectException(InvalidArgumentException::class);
        new Translator(['replacement-is-not-string' => 123]);
    }

    public function testTranslatePlaceholder()
    {
        $this->assertSame(
            'translated',
            (new Translator(['placeholder' => 'translated']))->translate('{placeholder}')
        );
    }

    public function testTranslateMultiplePlaceholders()
    {
        $this->assertSame(
            'A B',
            (new Translator(['a' => 'A', 'b' => 'B']))->translate('{a} {b}')
        );
    }

    public function testTranslateUnknown()
    {
        $this->assertSame(
            '{foo}',
            (new Translator([]))->translate('{foo}')
        );
    }
}
