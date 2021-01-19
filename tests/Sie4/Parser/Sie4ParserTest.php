<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\AccountInterface;
use byrokrat\accounting\Dimension\DimensionInterface;
use byrokrat\accounting\Exception\InvalidSieFileException;
use byrokrat\accounting\Exception\RuntimeException;
use byrokrat\accounting\MoneyFactory;
use byrokrat\accounting\Sie4\SieMetaData;
use Money\Money;

class Sie4ParserTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionWhenMetaDataIsNotSet()
    {
        $parser = new Sie4Parser();

        $this->expectException(RuntimeException::class);

        $parser->getParsedMetaData();
    }

    /**
     * Each line must start with a '#' marked label according to rule 5.3
     */
    public function testLabelRequired()
    {
        $parser = new Sie4Parser();

        $this->expectException(InvalidSieFileException::class);

        $parser->parse(
            "
                #FLAGGA 1
                this-is-not-a-label
            "
        );
    }

    /**
     * An optional \\r line ending char should be allowed according to rule 5.5
     */
    public function testEndOfLineCharacters()
    {
        $parser = new Sie4Parser();

        $parser->parse("#FLAGGA 1\r\n");

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Empty lines should be ignored according to rule 5.6
     */
    public function testEmptyLines()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                \t
                \t \t

                #FLAGGA 1
                \t
                \t \t

                #PROSA 1234

                \t
                \t \t
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: '1', description: '1234'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Tabs and spaces should be allowed as delimiters according to rule 5.7
     */
    public function testFieldDelimiters()
    {
        $parser = new Sie4Parser();

        $parser->parse("#FLAGGA\t \t1");

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Tabs and spaces should be allowed at the start of a line
     */
    public function testSpaceAtStartOfLine()
    {
        $parser = new Sie4Parser();

        $parser->parse(" \t #FLAGGA 1");

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Tabs and spaces should be allowed at the end of a line
     */
    public function testSpaceAtEndOfLine()
    {
        $parser = new Sie4Parser();

        $parser->parse("#FLAGGA 1 \t \t \n");

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Unknown labels should be ignored according to rule 7.1
     */
    public function testUnknownLabels()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #UNKNOWN foo bar
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Unknown fields at end of line should be ignored according to rule 7.3
     */
    public function testUnknownFieldsAtEndOfLine()
    {
        $parser = new Sie4Parser();

        $parser->parse("#FLAGGA 1 unknown-field-at-end-of-line");

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Possible integer representations
     */
    public function intTypeProvider()
    {
        return [
            ['1',      '1'],
            ['0',      '0'],
            ['-1',     '-1'],
            ['1234',   '1234'],
            ['"1234"', '1234'],
            ['"-1"',   '-1'],
        ];
    }

    /**
     * @dataProvider intTypeProvider
     */
    public function testIntegerType(string $raw, string $expected)
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA $raw
                #SIETYP $raw
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: $expected, sieVersion: $expected),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Possible date representations (see rule 5.10)
     */
    public function dateTypeProvider()
    {
        return [
            ['20160722',   '20160722'],
            ['"20160722"', '20160722'],
            ['201607',     '201607'],
            ['2016',       '2016'],
            ['20160722',   '20160722'],
        ];
    }

    /**
     * @dataProvider dateTypeProvider
     */
    public function testDateType(string $raw, string $expected)
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #GEN $raw
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: '1', generationDate: $expected),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Possible string representations according to rule 5.7
     */
    public function stringTypeProvider()
    {
        $validChars = array_reduce(
            array_merge([33], range(35, 126)),
            function ($carry, $char) {
                return $carry . chr($char);
            }
        );

        $values = [
            ['foo',           'foo',       'Regular string'],
            ['"foo"',         'foo',       'Quoted string'],
            ['"foo bar"',     'foo bar',   'Space inside quoted string'],
            ['"foo \\" bar"', 'foo " bar', 'Escaped quotes inside quoted string'],
            ['""',            '',          'The empty string'],
            [$validChars,     $validChars, 'Characters allowed'],
            ['åäöÅÄÖ',        'åäöÅÄÖ',    'Swedish special characters'],
        ];

        foreach ($values as list($raw, $expected)) {
            yield [iconv('UTF-8', 'CP437', $raw), $expected];
        }
    }

    /**
     * @dataProvider stringTypeProvider
     */
    public function testStringType(string $raw, string $expected)
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #FNAMN $raw
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: '1', companyName: $expected),
            $parser->getParsedMetaData()
        );
    }

    /**
     * Characters NOT allowed in fields according to rule 5.7
     */
    public function stringTypeInvalidCharsProvider()
    {
        foreach (range(0, 31) as $ascii) {
            yield [chr($ascii)];
        }
        yield [chr(127)];
    }

    /**
     * @dataProvider stringTypeInvalidCharsProvider
     */
    public function testStringTypeInvalidChars(string $invalidChar)
    {
        $parser = new Sie4Parser();

        $this->expectException(InvalidSieFileException::class);

        $parser->parse(
            "
                #FLAGGA 1
                #PROSA \"bar{$invalidChar}baz\"
            "
        );
    }

    public function testKsummaIgnored()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #KSUMMA
                #KSUMMA 1234
            "
        );

        $this->assertEquals(
            new SieMetaData(sieFlag: '1'),
            $parser->getParsedMetaData()
        );
    }

    public function testAccountingYear()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #RAR -1 20200101 20201231
                #RAR 0 20210101 20211231
            "
        );

        $expected = new SieMetaData(
            sieFlag: '1',
            accountingYearStart: '20210101',
            accountingYearEnd: '20211231',
            previousAccountingYearStart: '20200101',
            previousAccountingYearEnd: '20201231',
        );

        $this->assertEquals(
            $expected,
            $parser->getParsedMetaData()
        );
    }

    public function testAdditionalMetaData()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 0
                #FNAMN name
                #FNR X
                #FORMAT PC8
                #FTYP AB
                #GEN 20160824 HF
                #KPTYP BAS95
                #ORGNR 123456-1234
                #PROGRAM byrokrat 1.0
                #PROSA \"foo bar\" baz
                #SIETYP 4
                #TAXAR 2016
                #VALUTA EUR
            "
        );

        $expected = new SieMetaData(
            companyName: 'name',
            companyIdCode: 'X',
            charset: 'PC8',
            generationDate: '20160824',
            generatingUser: 'HF',
            accountPlanType: 'BAS95',
            companyOrgNr: '123456-1234',
            generatingProgram: 'byrokrat',
            generatingProgramVersion: '1.0',
            description: 'foo bar baz',
            sieVersion: '4',
            sieFlag: '0',
            taxationYear: '2016',
            currency: 'EUR',
        );

        $this->assertEquals(
            $expected,
            $parser->getParsedMetaData()
        );
    }

    /**
     * Possible account representations
     */
    public function accountTypeProvider()
    {
        return [
            ["#KONTO 1920 bank",               '1920', 'bank',    AccountInterface::TYPE_ASSET],
            ["#KONTO 1920 bank\n#KTYP 1920 S", '1920', 'bank',    AccountInterface::TYPE_DEBT],
            ["#KONTO 2000 debt",               '2000', 'debt',    AccountInterface::TYPE_DEBT],
            ["#KONTO 3000 earning",            '3000', 'earning', AccountInterface::TYPE_EARNING],
            ["#KONTO 4000 cost",               '4000', 'cost',    AccountInterface::TYPE_COST],
        ];
    }

    /**
     * @dataProvider accountTypeProvider
     */
    public function testAccountType(string $raw, string $expectedNr, string $expectedDesc, string $expectedType)
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                $raw
            "
        );

        $account = $container->select()->account($expectedNr);

        $this->assertSame(
            $expectedDesc,
            $account->getDescription()
        );

        $this->assertSame(
            $expectedType,
            $account->getType()
        );
    }

    public function testNoWarningOnAccountDuplication()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #KONTO 1920 bank
                #KONTO 1920 bank
            "
        );

        $this->assertInstanceOf(
            AccountInterface::class,
            $container->select()->account('1920')
        );
    }

    public function testObjectNesting()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #DIM 10 parent
                #UNDERDIM 20 child 10
                #OBJEKT 20 30 obj
            "
        );

        $parent = $container->select()->dimension('10');

        $this->assertSame(
            'parent',
            $parent->getDescription()
        );

        list($child) = $parent->getChildren();

        $this->assertSame(
            'child',
            $child->getDescription()
        );

        list($object) = $child->getChildren();

        $this->assertSame(
            'obj',
            $object->getDescription()
        );
    }

    /**
     * Possible object list representations
     */
    public function objectListTypeProvider()
    {
        return [
            ['{10 "foo"}',  '10', '10.foo'],
            ['{10 foo }',   '10', '10.foo'],
            ['{ 10 foo }',  '10', '10.foo'],
            ['{10 foo}',    '10', '10.foo'],
            ['{10 "fo}o"}', '10', '10.fo}o'],
            ['{10 "foo}"}', '10', '10.foo}'],
            ['{"10" foo}',  '10', '10.foo'],
        ];
    }

    /**
     * @dataProvider objectListTypeProvider
     */
    public function testObjectListType(string $list, string $parent, string $objId)
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #DIM $parent
                #OBJEKT $parent $objId
                #OIB 0 0 $list 10 0
            "
        );

        $this->assertInstanceOf(
            DimensionInterface::class,
            $container->select()->dimension($parent)
        );

        $obj = $container->select()->dimension($objId);

        $this->assertInstanceOf(
            DimensionInterface::class,
            $obj
        );

        $this->assertTrue(
            $obj->getSummary()->getIncomingBalance()->equals(Money::SEK('1000'))
        );
    }

    public function testNoWarningOnDimensionDuplication()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #DIM 1 foo
                #DIM 1 ignored
                #UNDERDIM 2 bar 1
                #UNDERDIM 2 ignored 1
            "
        );

        $this->assertSame(
            'foo',
            $container->select()->dimension('1')->getDescription()
        );

        $this->assertSame(
            'bar',
            $container->select()->dimension('2')->getDescription()
        );
    }

    public function testNoWarningOnObjectDuplication()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #OBJEKT 1 2 foo
                #OBJEKT 1 2 ignored
            "
        );

        $this->assertInstanceOf(
            DimensionInterface::class,
            $container->select()->dimension('1')
        );

        $this->assertSame(
            'foo',
            $container->select()->dimension('1.2')->getDescription()
        );
    }

    public function testObjectNameNotEqualToParent()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #DIM 666 dim
                #OBJEKT 666 666 obj
            "
        );

        $this->assertSame(
            'dim',
            $container->select()->dimension('666')->getDescription()
        );

        $this->assertSame(
            'obj',
            $container->select()->dimension('666.666')->getDescription()
        );
    }

    /**
     * Dimension default names according to rule 8.17
     */
    public function testDimensionDefaultNames()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #OBJEKT 8 666
            "
        );

        $this->assertSame(
            'Kund',
            $container->select()->dimension('8')->getDescription()
        );
    }

    /**
     * Dimension 2 should be a subdimension of 1 according to rule 8.17
     */
    public function testDimension2DefaultsTo1AsParent()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #OBJEKT 2 666
            "
        );

        $parent = $container->select()->dimension('1');

        $this->assertSame(
            'Kostnadsställe/resultatenhet',
            $parent->getDescription()
        );

        $this->assertSame(
            'Kostnadsbärare',
            $parent->getChildren()[0]->getDescription()
        );
    }

    /**
     * Possible currency representations according to rule 5.9
     */
    public function currencyTypeProvider()
    {
        return [
            ['SEK', '1',      Money::SEK('100')],
            ['SEK', '10.11',  Money::SEK('1011')],
            ['SEK', '10.1',   Money::SEK('1010')],
            ['SEK', '-1',     Money::SEK('-100')],
            ['SEK', '"1.00"', Money::SEK('100')],
            ['EUR', '1',      Money::EUR('100')],
        ];
    }

    /**
     * @dataProvider currencyTypeProvider
     */
    public function testCurrencyType(string $currency, string $rawMoney, Money $expectedMoney)
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #VALUTA $currency
                #IB 0 1920 $rawMoney
            "
        );

        $account = $container->select()->account('1920');

        $this->assertTrue(
            $account->getSummary()->getIncomingBalance()->equals($expectedMoney)
        );
    }

    public function testIbFromPreviousYears()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #IB -1 1920 100
            "
        );

        $account = $container->select()->account('1920');

        $this->assertSame(
            '100',
            $account->getAttribute(Sie4Parser::PREVIOUS_INCOMING_BALANCE_ATTRIBUTE)
        );
    }

    public function testIbFromUnknownYearsIsIgnored()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #IB -2 1920 100
            "
        );

        $accounts = $container->select()->accounts()->asArray();

        $this->assertEmpty($accounts);
    }

    public function testAccountOutgoingBalance()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #UB 0 1920 666
                #UB -1 1920 999
            "
        );

        $account = $container->select()->account('1920');

        $this->assertSame(
            '666',
            $account->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)
        );

        $this->assertSame(
            '999',
            $account->getAttribute(Sie4Parser::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE)
        );
    }

    public function testAccountOutgoingResult()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #RES 0 3000 666
                #RES -1 3000 999
            "
        );

        $account = $container->select()->account('3000');

        $this->assertSame(
            '666',
            $account->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)
        );

        $this->assertSame(
            '999',
            $account->getAttribute(Sie4Parser::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE)
        );
    }

    public function testObjectIncomingBalance()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #DIM 10 parent
                #OBJEKT 10 20 objA
                #OBJEKT 10 30 objB
                #OIB 0 1920 {10 \"20\" 10 \"30\"} 100 0
                #OIB -1 1920 {10 \"20\"} 200 0
            "
        );

        $objA = $container->select()->dimension('10.20');

        $this->assertTrue(
            $objA->getSummary()->getIncomingBalance()->equals(Money::SEK('10000'))
        );

        $this->assertSame(
            '200',
            $objA->getAttribute(Sie4Parser::PREVIOUS_INCOMING_BALANCE_ATTRIBUTE)
        );

        $objB = $container->select()->dimension('10.30');

        $this->assertTrue(
            $objB->getSummary()->getIncomingBalance()->equals(Money::SEK('10000'))
        );
    }

    public function testObjectOutgoingBalance()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #OUB 0 1920 {10 20} 100 0
                #OUB -1 1920 {10 20} 200 0
            "
        );

        $obj = $container->select()->dimension('10.20');

        $this->assertSame(
            '100',
            $obj->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)
        );

        $this->assertSame(
            '200',
            $obj->getAttribute(Sie4Parser::PREVIOUS_OUTGOING_BALANCE_ATTRIBUTE)
        );
    }

    public function testCreateVerifications()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
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
            "
        );

        $verifications = $container->select()->verifications()->asArray();

        $this->assertCount(2, $verifications);

        $this->assertSame(
            'Ver B',
            $verifications[1]->getDescription()
        );
    }

    public function testAddedTransactions()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #VER \"\" \"\" 20110104 \"Ver A\"
                {
                    #TRANS  3010 {} -100.00
                    #RTRANS 1920 {} 100.00
                    #TRANS  1920 {} 100.00
                }
            "
        );

        $ver = $container->select()->verifications()->first();

        $this->assertTrue(
            $ver->getTransactions()[1]->isAdded(),
            'Second transaction should be marked as added'
        );

        $this->assertCount(
            2,
            $ver->getTransactions(),
            'Transaction count should be 2 as #TRANS post following an #RTRANS should not count'
        );
    }

    public function testDeletedTransaction()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #VER \"\" \"\" 20110104 \"Ver A\"
                {
                    #BTRANS 1910 {} 100.00
                }
            "
        );

        $ver = $container->select()->verifications()->first();

        $this->assertTrue(
            $ver->getTransactions()[0]->isDeleted(),
            'Transaction should be marked as deleted'
        );

        $this->assertCount(1, $ver->getTransactions());
    }

    public function testParserResetsBetweenRuns()
    {
        $parser = new Sie4Parser();

        $parser->parse(
            "
                #FLAGGA 1
                #VER \"\" \"\" 20110104 \"Ver A\"
                {
                    #TRANS  3010 {} -100.00
                    #TRANS  1920 {} 100.00
                }
            "
        );

        $container = $parser->parse(
            "
                #FLAGGA 1
                #VER \"\" \"\" 20110104 \"Ver B\"
                {
                    #TRANS  3010 {} -100.00
                    #TRANS  1920 {} 100.00
                }
            "
        );

        $this->assertCount(1, $container->select()->verifications()->asArray());
    }

    public function testCreateVerificationSeries()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
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
            "
        );

        $seriesB = $container->select()
            ->verifications()
            ->whereAttributeValue(Sie4Parser::VERIFICATION_SERIES_ATTRIBUTE, 'B')
            ->asArray();

        $this->assertCount(1, $seriesB);

        $this->assertSame(
            'Ver B',
            $seriesB[0]->getDescription()
        );
    }

    public function testExceptionOnMissingTransactionAmont()
    {
        $parser = new Sie4Parser();

        $this->expectException(InvalidSieFileException::class);

        $parser->parse(
            "
                #FLAGGA 1
                #VER \"A\" \"\" 20110104
                {
                    #TRANS  1920 {}
                }
            "
        );
    }

    public function testSkippingOverOptionalArguments()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #VER \"A\" \"\" 20110104
                {
                    #TRANS  1920 {} 100 \"\" \"\" \"\" sign
                    #TRANS  1920 {} -100
                }
            "
        );

        $transactions = $container->select()->transactions()->asArray();

        $this->assertSame(
            'sign',
            $transactions[0]->getSignature()
        );
    }

    public function testValidateOutgoingAccountBalance()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #IB 0 1920 100
                #UB 0 1920 300
                #RES 0 3010 -200
                #VER \"\" \"1\" 20110104 \"\"
                {
                    #TRANS  3010 {} -100.00
                    #TRANS  1920 {} 100.00
                }
                #VER \"\" \"2\" 20110104 \"\"
                {
                    #TRANS  3010 {} -100.00
                    #TRANS  1920 {} 100.00
                }
            "
        );

        $moneyFactory = new MoneyFactory();

        $balanceAccount = $container->select()->account('1920');

        $this->assertEquals(
            $moneyFactory->createMoney($balanceAccount->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)),
            $balanceAccount->getSummary()->getOutgoingBalance()
        );

        $resultAccount = $container->select()->account('3010');

        $this->assertEquals(
            $moneyFactory->createMoney($resultAccount->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)),
            $resultAccount->getSummary()->getOutgoingBalance()
        );
    }

    public function testValidateOutgoingObjectBalance()
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(
            "
                #FLAGGA 1
                #OIB 0 0 {1 2} 100
                #OUB 0 0 {1 2} 200
                #VER \"\" \"1\" 20110104 \"\"
                {
                    #TRANS 3010 {} -100.00
                    #TRANS 1920 {1 2} 100.00
                }
            "
        );

        $moneyFactory = new MoneyFactory();

        $object = $container->select()->dimension('1.2');

        $this->assertEquals(
            $moneyFactory->createMoney($object->getAttribute(Sie4Parser::OUTGOING_BALANCE_ATTRIBUTE)),
            $object->getSummary()->getOutgoingBalance()
        );
    }
}
