<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\amount\Currency\{SEK, EUR};

/**
 * Tests the grammar specification in Grammar.peg
 *
 * Referenced rules are from the SIE specs dated 2008-09-30
 *
 * @covers byrokrat\accounting\Sie4\Grammar
 */
class GrammarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Assert that a parser callback method was called when parsing source
     */
    private function assertParser(string $method, array $args, string $source)
    {
        $parser = $this->getMockBuilder(Parser::CLASS)
            ->setMethods([$method])
            ->getMock();

        $parser->expects($this->once())
            ->method($method)
            ->with(...$args);

        $parser->parse($source);
    }

    /**
     * Each line must start with a '#' marked label according to rule 5.3
     */
    public function testLabelRequired()
    {
        $this->setExpectedException(\InvalidArgumentException::CLASS);
        (new Parser)->parse("this is not a label\n");
    }

    // TODO Tests for rule 5.4 are missing

    /**
     * An optional \\r line ending char should be allowed according to rule 5.5
     */
    public function testEndOfLineCharacters()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar']],
            "#FOO bar\r\n"
        );
    }

    /**
     * Empty lines should be ignored according to rule 5.6
     */
    public function testEmptyLines()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar']],
            "\n \n\t\n \t \n#FOO bar\n \n\t\n \t \n"
        );
    }

    /**
     * Tabs and spaces should be allowed as delimiters according to rule 5.7
     */
    public function testFieldDelimiters()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['foo', 'bar', 'baz']],
            "#FOO foo\t \tbar\tbaz\n"
        );
    }

    /**
     * Tabs and spaces should be allowed at the start of a line
     */
    public function testSpaceAtStartOfLine()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar']],
            " \t #FOO bar\n"
        );
    }

    /**
     * Tabs and spaces should be allowed at the end of a line
     */
    public function testSpaceAtEndOfLine()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar']],
            "#FOO bar\t \t\n"
        );
    }

    /**
     * Quoted fields should be allowed according to rule 5.7
     */
    public function testQuotedFields()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar']],
            "#FOO \"bar\"\n"
        );
    }

    /**
     * Spaces should be allowed within quoted fields according to rule 5.7
     */
    public function testSpaceInsideQuotedFields()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar baz']],
            "#FOO \"bar baz\"\n"
        );
    }

    /**
     * Escaped quotes should be allowed within quoted fields according to rule 5.7
     */
    public function testEscapedQuotesInsideQuotedFields()
    {
        $this->assertParser(
            'onUnknown',
            ['FOO', ['bar " baz']],
            "#FOO \"bar \\\" baz\"\n"
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
     * Test characters not allowed in a field according to rule 5.7
     *
     * @dataProvider invalidCharactersProvider
     */
    public function testInvalidCharacters($char)
    {
        $this->setExpectedException(\InvalidArgumentException::CLASS);
        (new Parser)->parse("#FOO \"bar{$char}baz\"\n");
    }

    /**
     * Test characters allowed according to rule 5.7
     *
     * Note that the quote (chr(34)) and space characters (chr(32)) are left out
     * as they are special cases.
     */
    public function testValidCharacters()
    {
        foreach (array_merge([33], range(35, 126)) as $ascii) {
            $this->assertParser(
                'onUnknown',
                ['FOO', [chr($ascii)]],
                "#FOO " . chr($ascii) . "\n"
            );
        }
    }

    public function booleanProvider()
    {
        return [
            ['0', false],
            ['1', true],
            ['"0"', false],
            ['"1"', true],
        ];
    }

    /**
     * @dataProvider booleanProvider
     */
    public function testBooleanFlagPost(string $flag, bool $boolval)
    {
        $this->assertParser(
            'onFlag',
            [$boolval],
            "#FLAGGA $flag\n"
        );
    }

    public function integerProvider()
    {
        return [
            ['1', 1],
            ['0', 0],
            ['-1', -1],
            ['1234', 1234],
            ['"1234"', 1234],
            ['"-1"', -1],
        ];
    }

    /**
     * @dataProvider integerProvider
     */
    public function testIntegerSieVersionPost(string $raw, int $intval)
    {
        $this->assertParser(
            'onSieVersion',
            [$intval],
            "#SIETYP $raw\n"
        );
    }

    public function currencyProvider()
    {
        return [
            ['1', new SEK('1')],
            ['10.11', new SEK('10.11')],
            ['10.1', new SEK('10.10')],
            ['-1', new SEK('-1')],
            ['"1.00"', new SEK('1')],
        ];
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testCurrencyIncomingBalancePost(string $raw, SEK $currency)
    {
        $this->assertParser(
            'onIncomingBalance',
            [0, '1920', $currency, 0],
            "#IB 0 1920 $raw 0\n"
        );
    }

    public function testCurrencPost()
    {
        $this->assertParser(
            'onIncomingBalance',
            [0, '1920', new EUR('10'), 0],
            "#VALUTA EUR\n#IB 0 1920 10 0\n"
        );
    }

    public function dateProvider()
    {
        return [
            ['20160722', new \DateTime('20160722')],
            ['"20160722"', new \DateTime('20160722')],
            ['201607', new \DateTime('20160701')],
            ['2016', new \DateTime('20160101')],
            ['20160722', new \DateTime('20160722')],
        ];
    }

    /**
     * @dataProvider dateProvider
     */
    public function testDates(string $raw, \DateTime $date)
    {
        $this->assertParser(
            'onMagnitudeDate',
            [$date],
            "#OMFATTN $raw\n"
        );
    }

    /**
     * Unknown labels should not trigger errors according to rule 7.1
     */
    public function testUnknownLabels()
    {
        $this->assertParser(
            'onUnknown',
            ['UNKNOWN', ['foo']],
            "#UNKNOWN foo\n"
        );
    }

    // TODO test for rule 7.3 is missing
}
