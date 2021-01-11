<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Dimension\AccountInterface;

/**
 * Phpunit data providers for the SIE types defined in Grammar
 */
trait TypeProviderTrait
{
    /**
     * Provider for possible boolean representations
     */
    public function booleanTypeProvider()
    {
        return [
            ['0',   false],
            ['1',   true],
            ['"0"', false],
            ['"1"', true],
        ];
    }

    /**
     * Provider for possible currency representations according to rule 5.9
     */
    public function currencyTypeProvider()
    {
        return [
            ['1',      '1'],
            ['10.11',  '10.11'],
            ['10.1',   '10.1'],
            ['-1',     '-1'],
            ['"1.00"', '1.00'],
        ];
    }

    /**
     * Provider for possibla date representations (see rule 5.10)
     */
    public function dateTypeProvider()
    {
        return [
            ['20160722',   new \DateTimeImmutable('20160722')],
            ['"20160722"', new \DateTimeImmutable('20160722')],
            ['201607',     new \DateTimeImmutable('20160701')],
            ['2016',       new \DateTimeImmutable('20160101')],
            ['20160722',   new \DateTimeImmutable('20160722')],
        ];
    }

    public function floatTypeProvider()
    {
        return [
            ['1.0', 1.0],
            ['1', 1.0],
            ['1.25', 1.25],
            ['0.0', 0.0],
            ['-1.25', -1.25],
            ['"1.0"', 1.0],
            ['"1"', 1.0],
            ['"1.25"', 1.25],
            ['"0.0"', 0.0],
            ['"-1.25"', -1.25],
        ];
    }

    /**
     * Provider for possible integer representations
     */
    public function intTypeProvider()
    {
        return [
            ['1',      1],
            ['0',      0],
            ['-1',     -1],
            ['1234',   1234],
            ['"1234"', 1234],
            ['"-1"',   -1],
        ];
    }

    /**
     * Provider for possible string representations according to rule 5.7
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
     * Creates an iterator of all characters NOT allowed in fields according to rule 5.7
     */
    public function stringTypeInvalidCharsProvider()
    {
        foreach (range(0, 31) as $ascii) {
            yield [chr($ascii)];
        }
        yield [chr(127)];
    }

    /**
     * Provider for possible account representations
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
     * Provider for possible object list representations
     */
    public function objectListTypeProvider()
    {
        return [
            ['{10 "foo"}', 'foo'],
            ['{10 foo }', 'foo'],
            ['{10 foo}', 'foo'],
            ['{10 "fo}o"}', 'fo}o'],
            ['{10 "foo}"}', 'foo}'],
            ['{10 foo}', 'foo'],
        ];
    }
}
