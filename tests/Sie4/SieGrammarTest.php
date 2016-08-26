<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Container;
use byrokrat\accounting\Exception;
use byrokrat\amount\Currency\SEK;

/**
 * Tests the grammar specification in Grammar.peg
 *
 * Referenced rules are from the SIE specs dated 2008-09-30
 *
 * @covers \byrokrat\accounting\Sie4\SieGrammar
 */
class SieGrammarTest extends \PHPUnit_Framework_TestCase
{
    use \byrokrat\accounting\utils\InterfaceAssertionsTrait, TypeProviderTrait;

    /**
     * Parse content and get resulting container
     */
    private function parse(string $content, string $logLevel = SieParserFactory::FAIL_ON_ERROR): Container
    {
        return (new SieParserFactory)->createParser($logLevel)->parse($content);
    }

    /**
     * Each line must start with a '#' marked label according to rule 5.3
     */
    public function testLabelRequired()
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse(
            "
                #FLAGGA 1
                this-is-not-a-label
            ",
            SieParserFactory::FAIL_ON_WARNING
        );
    }

    /**
     * An optional \\r line ending char should be allowed according to rule 5.5
     */
    public function testEndOfLineCharacters()
    {
        $this->assertEquals(
            $this->parse("#FLAGGA 1\n"),
            $this->parse("#FLAGGA 1\r\n")
        );
    }

    /**
     * Empty lines should be ignored according to rule 5.6
     */
    public function testEmptyLines()
    {
        $this->assertEquals(
            $this->parse("#FLAGGA 1\n#KSUMMA\n#KSUMMA 1234\n"),
            $this->parse("
                \t
                \t \t

                #FLAGGA 1
                \t
                \t \t

                #KSUMMA
                \t
                \t \t

                #KSUMMA 1234

                \t
                \t \t
            ")
        );
    }

    /**
     * Tabs and spaces should be allowed as delimiters according to rule 5.7
     */
    public function testFieldDelimiters()
    {
        $this->assertEquals(
            $this->parse("#FLAGGA\t1\n"),
            $this->parse("#FLAGGA\t \t1\n")
        );
    }

    /**
     * Tabs and spaces should be allowed at the start of a line
     */
    public function testSpaceAtStartOfLine()
    {
        $this->assertEquals(
            $this->parse("#FLAGGA 1\n"),
            $this->parse(" \t #FLAGGA 1\n")
        );
    }

    /**
     * Tabs and spaces should be allowed at the end of a line
     */
    public function testSpaceAtEndOfLine()
    {
        $this->assertEquals(
            $this->parse("#FLAGGA 1\n"),
            $this->parse("#FLAGGA 1 \t \t \n")
        );
    }

    /**
     * Unknown fields at end of line should not trigger errors according to rule 7.3
     */
    public function testUnknownFieldsAtEndOfLine()
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse(
            "#FLAGGA 1 unknown-field-at-end-of-line\n",
            SieParserFactory::FAIL_ON_NOTICE
        );
    }

    /**
     * Unknown labels should not trigger errors according to rule 7.1
     */
    public function testUnknownLabels()
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse(
            "
                #FLAGGA 1
                #UNKNOWN foo bar
            ",
            SieParserFactory::FAIL_ON_NOTICE
        );
    }

    /**
     * @dataProvider booleanTypeProvider
     */
    public function testBooleanType(string $raw, bool $boolval)
    {
        $this->assertAttributes(
            [
                'FLAGGA' => $boolval
            ],
            $this->parse("
                #FLAGGA $raw
            ")
        );
    }

    /**
     * @dataProvider dateTypeProvider
     */
    public function testDateType(string $raw, \DateTimeImmutable $date)
    {
        $this->assertAttributes(
            [
                'OMFATTN' => $date
            ],
            $this->parse("
                #FLAGGA 1
                #OMFATTN $raw
            ")
        );
    }

    /**
     * @dataProvider intTypeProvider
     */
    public function testIntegerType(string $raw, int $intval)
    {
        $this->assertAttributes(
            [
                'SIETYP' => $intval
            ],
            $this->parse("
                #FLAGGA 1
                #SIETYP $raw
            ")
        );
    }

    /**
     * @dataProvider stringTypeProvider
     */
    public function testStringType(string $raw, string $parsed)
    {
        $this->assertAttributes(
            [
                'FNAMN' => $parsed
            ],
            $this->parse("
                #FLAGGA 1
                #FNAMN $raw
            ")
        );
    }

    /**
     * Test characters not allowed in a field according to rule 5.7
     *
     * @dataProvider stringTypeInvalidCharsProvider
     */
    public function testStringTypeInvalidChars(string $char)
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse("
            #FLAGGA 1
            #VALUTA \"bar{$char}baz\"
        ");
    }

    public function testNoticeOnKsumma()
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse(
            "
                #FLAGGA 1
                #KSUMMA
                #KSUMMA 1234
            ",
            SieParserFactory::FAIL_ON_NOTICE
        );
    }

    public function testKsumm()
    {
        $this->assertAttributes(
            [
                'KSUMMA' => 1234
            ],
            $this->parse("
                #FLAGGA 1
                #KSUMMA
                #KSUMMA 1234
            ")
        );
    }

    public function testIdentificationRows()
    {
        $this->assertAttributes(
            [
                'BKOD' => 1234,
                'ADRESS' => ['A', 'B', 'C', 'D'],
                'FNAMN' => 'name',
                'FNR' => 'X',
                'FORMAT' => 'PC8',
                'FTYP' => 'AB',
                'GEN' => [new \DateTimeImmutable('20160824'), 'HF'],
                'KPTYP' => 'BAS95',
                'OMFATTN' => new \DateTimeImmutable('20160101'),
                'ORGNR' => ['123456-1234', 0, 0],
                'PROGRAM' => ['byrokrat', '1.0'],
                'PROSA' => 'foobar',
                'RAR 0' =>[new \DateTimeImmutable('20160101'), new \DateTimeImmutable('20161231')],
                'RAR -1' =>[new \DateTimeImmutable('20150101'), new \DateTimeImmutable('20151231')],
                'SIETYP' => 4,
                'TAXAR' => 2016,
                'VALUTA' => 'EUR',
            ],
            $this->parse("
                #FLAGGA 1
                #ADRESS A B C D
                #BKOD 1234
                #FNAMN name
                #FNR X
                #FORMAT PC8
                #FTYP AB
                #GEN 20160824 HF
                #KPTYP BAS95
                #OMFATTN 20160101
                #ORGNR 123456-1234
                #PROGRAM byrokrat 1.0
                #PROSA foobar
                #RAR 0 20160101 20161231
                #RAR -1 20150101 20151231
                #SIETYP 4
                #TAXAR 2016
                #VALUTA EUR
            ")
        );
    }

    /**
     * Only charset PC8 is supported
     */
    public function testExceptionOnInvalidCharset()
    {
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->parse(
            "
                #FLAGGA 1
                #FORMAT not-PC8
            ",
            SieParserFactory::FAIL_ON_WARNING
        );
    }

    /**
     * @dataProvider accountTypeProvider
     */
    public function testAccountType(string $raw, int $expectedNr, string $expectedDesc, string $expectedClass)
    {
        $account = $this->parse("#FLAGGA 1\n$raw\n")->query()->findAccountFromNumber($expectedNr);

        $this->assertSame(
            $expectedDesc,
            $account->getDescription()
        );

        $this->assertInstanceOf(
            $expectedClass,
            $account
        );
    }

    public function testEnhet()
    {
        $content = $this->parse("
            #FLAGGA 1
            #ENHET 1920 kr
        ");

        $this->assertSame(
            'kr',
            $content->query()->findAccountFromNumber(1920)->getAttribute('unit')
        );
    }

    public function testSru()
    {
        $content = $this->parse("
            #FLAGGA 1
            #SRU 1920 2000
        ");

        $this->assertSame(
            2000,
            $content->query()->findAccountFromNumber(1920)->getAttribute('sru')
        );
    }

    public function testObjectType()
    {
        $object = $this->parse("
            #FLAGGA 1
            #DIM 10 parent
            #UNDERDIM 20 child 10
            #OBJEKT 20 30 obj
        ")->query()->findDimensionFromNumber(30);

        $this->assertSame(
            'obj',
            $object->getDescription()
        );

        $this->assertSame(
            'child',
            $object->getParent()->getDescription()
        );

        $this->assertSame(
            'parent',
            $object->getParent()->getParent()->getDescription()
        );
    }

    /**
     * @dataProvider currencyTypeProvider
     */
    public function testIb(string $raw, SEK $money)
    {
        $account = $this->parse("
            #FLAGGA 1
            #IB 0 1920 $raw 0
            #IB -1 1920 $raw 100
        ")->query()->findAccountFromNumber(1920);

        $this->assertEquals(
            $money,
            $account->getAttribute('IB 0')[0]
        );

        $this->assertEquals(
            100,
            $account->getAttribute('IB -1')[1]
        );
    }

    public function testOib()
    {
        $content = $this->parse("
            #FLAGGA 1
            #DIM 10 parent
            #OBJEKT 10 20 objA
            #OBJEKT 10 30 objB
            #OIB 0 1920 {10 20 10 30} 100 0
            #OIB -1 1920 {10 20} 200 1
        ");

        $objB = $content->query()->findDimensionFromNumber(30);

        $this->assertEquals(
            new SEK('100'),
            $objB->getAttribute('IB 0')[0]
        );

        $objA = $content->query()->findDimensionFromNumber(20);

        $this->assertEquals(
            new SEK('100'),
            $objA->getAttribute('IB 0')[0]
        );

        $this->assertEquals(
            new SEK('200'),
            $objA->getAttribute('IB -1')[0]
        );

        $this->assertEquals(
            1,
            $objA->getAttribute('IB -1')[1]
        );
    }
}
