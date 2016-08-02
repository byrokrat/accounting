<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Account;
use byrokrat\amount\Currency\SEK;

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
     * Helper that prepends a #FLAGGA post and add line breaks between posts
     */
    private function buildContent(...$posts)
    {
        return "#FLAGGA 1\n" . implode("\n", $posts) . "\n";
    }

    /**
     * Each line must start with a '#' marked label according to rule 5.3
     */
    public function testLabelRequired()
    {
        $this->setExpectedException(\InvalidArgumentException::CLASS);
        (new Parser)->parse(
            $this->buildContent("this is not a label")
        );
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
            $this->buildContent("#FOO bar\r\n")
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
            $this->buildContent(" ", "\t", " \t ", "#FOO bar", " ", "\t", " \t ")
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
            $this->buildContent("#FOO foo\t \tbar\tbaz")
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
            $this->buildContent(" \t #FOO bar")
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
            $this->buildContent("#FOO bar\t \t")
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
            $this->buildContent("#FOO \"bar\"")
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
            $this->buildContent("#FOO \"bar baz\"")
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
            $this->buildContent("#FOO \"bar \\\" baz\"")
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
        (new Parser)->parse(
            $this->buildContent("#FOO \"bar{$char}baz\"")
        );
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
                $this->buildContent("#FOO " . chr($ascii))
            );
        }
    }

    /**
     * Valid boolean representations
     */
    public function validBooleansProvider()
    {
        return [
            ['0', false],
            ['1', true],
            ['"0"', false],
            ['"1"', true],
        ];
    }

    /**
     * Valid dates (see rule 5.10)
     */
    public function validDatesProvider()
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
     * Valid integer representations
     */
    public function validIntegersProvider()
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
     * Valid money representations according to rule 5.9
     */
    public function validMoneyProvider()
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
     * @dataProvider validBooleansProvider
     */
    public function testOnFlagga(string $flag, bool $boolval)
    {
        $this->assertParser(
            'onFlagga',
            [$boolval],
            "#FLAGGA $flag\n"
        );
    }

    public function testOnAdress()
    {
        $this->assertParser(
            'onAdress',
            ['A', 'B', 'C', 'D'],
            $this->buildContent("#ADRESS A B C D")
        );
    }

    /**
     * @dataProvider validDatesProvider
     */
    public function testOnOmfattn(string $raw, \DateTime $date)
    {
        $this->assertParser(
            'onOmfattn',
            [$date],
            $this->buildContent("#OMFATTN $raw")
        );
    }

    /**
     * @dataProvider validIntegersProvider
     */
    public function testOnSietyp(string $raw, int $intval)
    {
        $this->assertParser(
            'onSietyp',
            [$intval],
            $this->buildContent("#SIETYP $raw")
        );
    }

    public function testOnValuta()
    {
        $this->assertParser(
            'onValuta',
            ['EUR'],
            $this->buildContent("#VALUTA EUR")
        );
    }

    public function testOnKonto()
    {
        $this->assertParser(
            'onKonto',
            [1920, 'bank'],
            $this->buildContent("#KONTO 1920 bank")
        );
    }

    public function testOnKtyp()
    {
        $this->assertParser(
            'onKtyp',
            [1920, 'S'],
            $this->buildContent("#KTYP 1920 S")
        );
    }

    public function testOnEnhet()
    {
        $this->assertParser(
            'onEnhet',
            [1920, 'kr'],
            $this->buildContent("#ENHET 1920 kr")
        );
    }

    public function testOnSru()
    {
        $this->assertParser(
            'onSru',
            [1920, '2000'],
            $this->buildContent("#SRU 1920 2000")
        );
    }

    /**
     * @dataProvider validMoneyProvider
     */
    public function testOnIb(string $raw, SEK $momey)
    {
        $this->assertParser(
            'onIb',
            [0, new Account\Asset(1920, 'UNSPECIFIED'), $momey, 0],
            $this->buildContent("#IB 0 1920 $raw 0")
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
            $this->buildContent("#UNKNOWN foo")
        );
    }

    // TODO test for rule 7.3 is missing
}
