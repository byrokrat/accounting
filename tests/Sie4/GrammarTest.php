<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * Tests the grammar specification in Grammar.peg
 *
 * Referenced rules are from the SIE specs dated 2008-09-30
 *
 * @covers \Sie4\Grammar.php
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
        $this->assertSieEquals(
            "#FOO bar\n",
            "#FOO bar\r\n",
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

    public function testQuotedFields()
    {
        return $this->markTestSkipped('Not implemented...');

        $this->assertSieSyntax(
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
            "#FOO \" \\\" \"\n",
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
     * @depends      testQuotedFields Quotes are needed to validate that tab is disallowed inside fields
     */
    public function testInvalidCharacters($char)
    {
        $this->assertInvalidSieSyntax(
            "#FOO \"bar{$char}baz\"\n",
            "Character '$char' (Ascii " . ord($char) . ") should not be allowed in a field according to rule 5.7"
        );
    }

    public function testValidCharacters()
    {
        return $this->markTestSkipped('Not implemented...');

        // The quote (chr(34)) and space characters (chr(32)) are left out as they are special cases
        $field = chr(33);
        foreach (range(35, 126) as $ascii) {
            $field .= chr($ascii);
        }

        $this->assertSieSyntax(
            "#FOO $field\n",
            "Ascii characters 32-126 should be allowed according to rule 5.7"
        );
    }

    // TODO tests for rules 5.8 to 5.10 are missing
}
