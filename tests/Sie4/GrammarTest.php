<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * Tests the grammar specification in Grammar.peg
 *
 * Referenced rules are from the SIE specs dated 2008-09-30
 *
 * @covers byrokrat\accounting\Sie4\Grammar
 */
class GrammarTest extends GrammarTestCase
{
    public function testRequiredLabels()
    {
        $this->assertInvalidSieSyntax(
            "this is not a label\n",
            "Each line must start with a '#' marked label according to rule 5.3"
        );
    }

    // TODO Tests for rule 5.4 are missing

    public function testEndOfLineCharacters()
    {
        $this->assertSieLexemes(
            [['#FLAGGA', '1']],
            "#FLAGGA 1\r\n",
            "An optional \\r line ending char should be allowed according to rule 5.5"
        );
    }

    public function testEmptyLines()
    {
        $this->assertSieLexemes(
            [],
            "\n \n\t\n \t \n",
            "Empty lines should be ignored according to rule 5.6"
        );
    }

    public function testFieldDelimiters()
    {
        $this->assertSieLexemes(
            [['#FOO', 'foo', 'bar', 'baz']],
            "#FOO foo\t \tbar\tbaz\n",
            "Tabs and spaces should be allowed as delimiters according to rule 5.7"
        );
    }

    public function testSpaceAtStartOfLine()
    {
        $this->assertSieLexemes(
            [['#FLAGGA', '1']],
            " \t #FLAGGA 1\n",
            "Tabs and spaces should be allowed at the start of a line"
        );
    }

    public function testSpaceAtEndOfLine()
    {
        $this->assertSieLexemes(
            [['#FLAGGA', '1']],
            "#FLAGGA 1\t \t\n",
            "Tabs and spaces should be allowed at the end of a line"
        );
    }

    public function testQuotedFields()
    {
        $this->assertSieLexemes(
            [['#FOO', "bar"]],
            "#FOO \"bar\"\n",
            "Quoted fields should be allowed according to rule 5.7"
        );

        $this->assertSieLexemes(
            [['#FOO', "bar baz"]],
            "#FOO \"bar baz\"\n",
            "Spaces should be allowed within quoted fields according to rule 5.7"
        );

        $this->assertSieLexemes(
            [['#FOO', 'bar " baz']],
            "#FOO \"bar \\\" baz\"\n",
            "Escaped quotes should be allowed within quoted fields according to rule 5.7"
        );
    }

    /**
     * Creates an iterator of all characters NOT allowed in fields according to rule 5.7
     */
    public function invalidCharactersProvider()
    {
        foreach (range(0, 31) as $ascii) {
            yield [chr($ascii)];
        }
        yield [chr(127)];
    }

    /**
     * @dataProvider invalidCharactersProvider
     */
    public function testInvalidCharacters($char)
    {
        $this->assertInvalidSieSyntax(
            "#FOO \"bar{$char}baz\"\n",
            "Character '$char' (Ascii " . ord($char) . ") should not be allowed in a field according to rule 5.7"
        );
    }

    /**
     * The quote (chr(34)) and space characters (chr(32)) are left out as they are special cases
     */
    public function testValidCharacters()
    {
        foreach (array_merge([33], range(35, 126)) as $ascii) {
            $this->assertSieSyntax(
                "#FOO " . chr($ascii) . "\n",
                "Ascii character $ascii (" . chr($ascii) . ") should be allowed according to rule 5.7"
            );
        }
    }

    // TODO tests for rules 5.8 to 5.10 are missing

    public function testUnknownLabels()
    {
        $this->assertSieLexemes(
            [['#UNKNOWN', 'foo']],
            "#UNKNOWN foo\n",
            "Unknown labels should not trigger errors according to rule 7.1"
        );
    }

    // TODO test for rule 7.3 is missing
}
