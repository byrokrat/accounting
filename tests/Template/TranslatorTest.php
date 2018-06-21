<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Template;

class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    public function testTranslateSingleValue()
    {
        $this->assertSame(
            ['key' => 'translated'],
            (new Translator(['placeholder' => 'translated']))->translate(['key' => '{placeholder}'])
        );
    }

    public function testTranslateMultipleValues()
    {
        $this->assertSame(
            ['keyA' => 'A', 'keyB' => 'B'],
            (new Translator(['a' => 'A', 'b' => 'B']))->translate(['keyA' => '{a}', 'keyB' => '{b}'])
        );
    }

    public function testTranslateMultiplePlaceholdersInOneValue()
    {
        $this->assertSame(
            ['key' => 'A B'],
            (new Translator(['a' => 'A', 'b' => 'B']))->translate(['key' => '{a} {b}'])
        );
    }

    public function testIgnoreUnknownPlaceholders()
    {
        $this->assertSame(
            ['key' => '{placeholder}'],
            (new Translator([]))->translate(['key' => '{placeholder}'])
        );
    }

    public function testRecursiveTranslation()
    {
        $this->assertSame(
            [
                'foo' => [
                    'keyA' => 'A',
                    'keyB' => 'B',
                ],
                'bar' => [
                    'A',
                    'B'
                ]
            ],
            (new Translator(['a' => 'A', 'b' => 'B']))->translate([
                'foo' => ['keyA' => '{a}', 'keyB' => '{b}'],
                'bar' => ['{a}', '{b}']
            ])
        );
    }
}
