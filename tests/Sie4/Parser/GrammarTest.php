<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\amount\Amount;
use byrokrat\amount\Currency\SEK;

/**
 * Tests the grammar specification in Grammar.peg
 *
 * Referenced rules are from the SIE specs dated 2008-09-30
 */
class GrammarTest extends \PHPUnit\Framework\TestCase
{
    use TypeProviderTrait;

    /**
     * Parse content and get resulting container
     */
    private function parse(string $content): Container
    {
        return (new Sie4ParserFactory)->createParser()->parse($content);
    }

    /**
     * Each line must start with a '#' marked label according to rule 5.3
     */
    public function testLabelRequired()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                this-is-not-a-label
            "
        );

        $this->assertRegExp('/invalid line/', implode($parser->getErrorLog()));
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
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "#FLAGGA 1 unknown-field-at-end-of-line\n"
        );

        $this->assertRegExp('/unknown field/', implode($parser->getErrorLog()));
    }

    /**
     * Unknown labels should not trigger errors according to rule 7.1
     */
    public function testUnknownLabels()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #UNKNOWN foo bar
            "
        );

        $this->assertRegExp('/unknown statement/', implode($parser->getErrorLog()));
    }

    /**
     * @dataProvider booleanTypeProvider
     */
    public function testBooleanType(string $raw, bool $boolval)
    {
        $this->assertAttributes(
            [
                'flag' => $boolval
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
                'period_end_date' => $date
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
                'sie_version' => $intval
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
                'company_name' => $parsed
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
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse("
            #FLAGGA 1
            #VALUTA \"bar{$char}baz\"
        ");

        $this->assertRegExp('/expecting end of file/', implode($parser->getErrorLog()));
    }

    public function testNoticeOnKsumma()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #KSUMMA
                #KSUMMA 1234
            "
        );

        $this->assertRegExp('/Checksum detected but currently not handled/', implode($parser->getErrorLog()));
    }

    public function testKsumm()
    {
        $this->assertAttributes(
            [
                'checksum' => 1234
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
                'company_sni_code' => 1234,
                'company_address' => [
                    'contact' => 'A',
                    'street' => 'B',
                    'postal' => 'C',
                    'phone' => 'D',
                ],
                'company_name' => 'name',
                'company_id' => 'X',
                'charset' => 'PC8',
                'company_type' => 'AB',
                'generation_date' => new \DateTimeImmutable('20160824'),
                'generating_user' => 'HF',
                'account_plan_type' => 'BAS95',
                'period_end_date' => new \DateTimeImmutable('20160101'),
                'company_org_nr' => ['123456-1234', 0, 0],
                'generating_program' => 'byrokrat',
                'generating_program_version' => '1.0',
                'description' => 'foo bar baz',
                'financial_year[0]' =>[new \DateTimeImmutable('20160101'), new \DateTimeImmutable('20161231')],
                'financial_year[-1]' =>[new \DateTimeImmutable('20150101'), new \DateTimeImmutable('20151231')],
                'sie_version' => 4,
                'taxation_year' => 2016,
                'currency' => 'EUR',
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
                #PROSA \"foo bar\" baz
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
    public function testErrorOnInvalidCharset()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #FORMAT not-PC8
            "
        );

        $this->assertRegExp('/Unknown charset/', implode($parser->getErrorLog()));
    }

    /**
     * @dataProvider accountTypeProvider
     */
    public function testAccountType(string $raw, string $expectedNr, string $expectedDesc, string $expectedClass)
    {
        $account = $this->parse("#FLAGGA 1\n$raw\n")->select()->getAccount($expectedNr);

        $this->assertSame(
            $expectedDesc,
            $account->getDescription()
        );

        $this->assertInstanceOf(
            $expectedClass,
            $account
        );
    }

    public function testWarningOnAccountDuplication()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #KONTO 1920 bank
                #KONTO 1920 bank
            "
        );

        $this->assertRegExp('/Overwriting previously created account/', implode($parser->getErrorLog()));
    }

    public function testEnhet()
    {
        $content = $this->parse("
            #FLAGGA 1
            #ENHET 1920 kr
        ");

        $this->assertSame(
            'kr',
            $content->select()->getAccount('1920')->getAttribute('unit')
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
            $content->select()->getAccount('1920')->getAttribute('sru')
        );
    }

    public function testWarningOnMissingSruAccount()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #SRU
            "
        );

        $this->assertRegExp('/Expected account/', implode($parser->getErrorLog()));
    }

    public function testWarningOnMissingSruNumber()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #SRU 1920
            "
        );

        $this->assertRegExp('/Expected SRU code/', implode($parser->getErrorLog()));
    }

    public function testObjectType()
    {
        $object = $this->parse("
            #FLAGGA 1
            #DIM 10 parent
            #UNDERDIM 20 child 10
            #OBJEKT 20 30 obj
        ")->select()->getDimension('30');

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
     * @dataProvider objectListTypeProvider
     */
    public function testObjectListType(string $list, string $objId)
    {
        $this->assertInstanceOf(
            DimensionInterface::CLASS,
            $this->parse("
                #FLAGGA 1
                #OIB 0 0 $list 0 0
            ")->select()->getDimension($objId)
        );
    }

    public function testWarningOnDimensionDuplication()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #DIM 1 name
                #UNDERDIM 1 name 1
            "
        );

        $this->assertRegExp('/Overwriting previously created dimension/', implode($parser->getErrorLog()));
    }

    public function testWarningOnObjectDuplication()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #OBJEKT 1 obj desc
                #OBJEKT 1 obj desc
            "
        );

        $this->assertRegExp('/Overwriting previously created object/', implode($parser->getErrorLog()));
    }

    /**
     * @dataProvider currencyTypeProvider
     */
    public function testCurrencyType(string $raw, SEK $money)
    {
        $account = $this->parse("
            #FLAGGA 1
            #IB 0 1920 $raw
        ")->select()->getAccount('1920');

        $this->assertEquals(
            $money,
            $account->getAttribute('incoming_balance')
        );

        $this->assertEquals(
            $money,
            $account->getAttribute('incoming_balance[0]')
        );
    }

    public function testOib()
    {
        $content = $this->parse("
            #FLAGGA 1
            #DIM 10 parent
            #OBJEKT 10 20 objA
            #OBJEKT 10 30 objB
            #OIB 0 1920 {10 \"20\" 10 \"30\"} 100 0
            #OIB -1 1920 {10 \"20\"} 200 1
        ");

        $objB = $content->select()->getDimension('30');

        $this->assertEquals(
            new SEK('100'),
            $objB->getAttribute('incoming_balance')
        );

        $objA = $content->select()->getDimension('20');

        $this->assertEquals(
            new SEK('100'),
            $objA->getAttribute('incoming_balance')
        );

        $this->assertEquals(
            new SEK('200'),
            $objA->getAttribute('incoming_balance[-1]')
        );

        $this->assertEquals(
            new Amount('1'),
            $objA->getAttribute('incoming_quantity[-1]')
        );
    }

    public function testCreateVerifications()
    {
        $verifications = $this->parse("
            #FLAGGA 1
            #VER \"\" \"1\" 20110104 \"Ver A\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
            #VER \"\" \"2\" 20110104 \"Ver B\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
        ")->select()->verifications()->asArray();

        $this->assertCount(2, $verifications);

        $this->assertSame(
            'Ver B',
            $verifications[1]->getDescription()
        );
    }

    public function testAddedTransactions()
    {
        $ver = $this->parse("
            #FLAGGA 1
            #VER \"\" \"\" 20110104 \"Ver A\"
            {
                #TRANS  3010 {} -100.00
                #RTRANS 1920 {} 100.00
                #TRANS  1920 {} 100.00
            }
        ")->select()->verifications()->getFirst();

        $this->assertInstanceOf(
            \byrokrat\accounting\Transaction\AddedTransaction::CLASS,
            $ver->getTransactions()[1]
        );

        $this->assertCount(
            2,
            $ver->getTransactions(),
            'Transaction count should be 2 as #TRANS post following an #RTRANS should not count'
        );
    }

    public function testDeletedTransaction()
    {
        $ver = $this->parse("
            #FLAGGA 1
            #VER \"\" \"\" 20110104 \"Ver A\"
            {
                #TRANS  3010 {} -100.00
                #BTRANS 1910 {} 100.00
                #RTRANS 1920 {} 100.00
                #TRANS  1920 {} 100.00
            }
        ")->select()->verifications()->getFirst();

        $this->assertInstanceOf(
            \byrokrat\accounting\Transaction\DeletedTransaction::CLASS,
            $ver->getTransactions()[1]
        );

        $this->assertCount(
            3,
            $ver->getTransactions(),
            'Transaction count should be 3 as #TRANS post following an #RTRANS should not count'
        );
    }

    public function testParserResetsBetweenRuns()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse("
            #FLAGGA 1
            #VER \"\" \"\" 20110104 \"Ver A\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
        ");

        $container = $parser->parse("
            #FLAGGA 1
            #VER \"\" \"\" 20110104 \"Ver B\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
        ");

        $this->assertCount(1, $container->select()->verifications()->asArray());
    }

    public function testCreateVerificationSeries()
    {
        $seriesA = $this->parse("
            #FLAGGA 1
            #VER \"A\" \"1\" 20110104 \"Ver A\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
            #VER \"B\" \"1\" 20110104 \"Ver B\"
            {
                #TRANS  3010 {} -100.00
                #TRANS  1920 {} 100.00
            }
        ")->select()->verifications()->whereAttribute('series', 'B')->asArray();

        $this->assertCount(1, $seriesA);

        $this->assertSame(
            'Ver B',
            $seriesA[0]->getDescription()
        );
    }

    public function testWarningOnMissingTransactionAmont()
    {
        $parser = (new Sie4ParserFactory)->createParser();

        $parser->parse(
            "
                #FLAGGA 1
                #VER \"A\" \"\" 20110104
                {
                    #TRANS  1920 {}
                }
            "
        );

        $this->assertRegExp('/Expected monetary amount/', implode($parser->getErrorLog()));
    }

    public function testSkippingOverOptionalArguments()
    {
        $transactions = $this->parse(
            "
                #FLAGGA 1
                #KONTO 1920 in
                #VER \"A\" \"\" 20110104
                {
                    #TRANS  1920 {} 100 \"\" \"\" 2.5
                    #TRANS  1920 {} -100
                }
            "
        )->select()->transactions()->asArray();

        $this->assertEquals(
            new Amount('2.5'),
            $transactions[0]->getQuantity()
        );
    }

    public function testPbudget()
    {
        $account = $this->parse("
            #FLAGGA 1
            #PBUDGET 0 201608 1920 {} 100 1
        ")->select()->getAccount('1920');

        $this->assertEquals(
            $account->getAttribute('period_budget_balance[0.201608]'),
            new SEK('100')
        );

        $this->assertEquals(
            $account->getAttribute('period_budget_quantity[0.201608]'),
            new Amount('1')
        );
    }

    public function testPsaldo()
    {
        $account = $this->parse("
            #FLAGGA 1
            #PSALDO 0 201608 1920 {} 100 1
        ")->select()->getAccount('1920');

        $this->assertEquals(
            $account->getAttribute('period_balance[0.201608]'),
            new SEK('100')
        );

        $this->assertEquals(
            $account->getAttribute('period_quantity[0.201608]'),
            new Amount('1')
        );
    }

    /**
     * Assert that attributes are set
     */
    private function assertAttributes(array $expectedAttr, AttributableInterface $attributable)
    {
        foreach ($expectedAttr as $key => $value) {
            $this->assertEquals(
                $value,
                $attributable->getAttribute($key),
                "Failed asserting that attributable contains attribute $key equal to " . var_export($value, true)
            );
        }
    }
}
