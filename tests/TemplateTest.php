<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\accounting\Transaction\Transaction;
use byrokrat\amount\Amount;

/**
 * @covers \byrokrat\accounting\Template
 */
class TemplateTest extends \PHPUnit\Framework\TestCase
{
    use utils\AttributableTestsTrait, utils\DescriptionTestsTrait;

    static private $translations;

    static private $container;

    public static function setUpBeforeClass()
    {
        self::$translations = [
            'bank' => '1920',
            'in' => '3000',
            'amount' => '400',
            'quantity' => '10',
            'dim' => '1',
            'attr' => 'foobar'
        ];

        self::$container = new Query([
            new Dimension\AssetAccount('1920', 'Bank'),
            new Dimension\EarningAccount('3000', 'Incomes'),
            new Dimension\Dimension('1'),
            new Dimension\Dimension('2')
        ]);
    }

    public function translationsProvider()
    {
        $verWithAttrFooBar = new Verification;
        $verWithAttrFooBar->setAttribute('foo', 'bar');

        $verWithAttrFoobarFoobar = new Verification;
        $verWithAttrFoobarFoobar->setAttribute('foobar', 'foobar');

        return [
            [
                (new Verification)->addTransaction(
                    new Transaction(new Dimension\EarningAccount('3000', 'Incomes'), new Amount('-400'))
                ),
                [['{in}', '-400']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(new Dimension\AssetAccount('1920', 'Bank'), new Amount('400'))
                ),
                [['1920', '{amount}']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(new Dimension\AssetAccount('1920', 'Bank'), new Amount('100'), new Amount('10'))
                ),
                [['1920', '100', '{quantity}']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(
                        new Dimension\AssetAccount('1920', 'Bank'),
                        new Amount('100'),
                        new Amount('1'),
                        new Dimension\Dimension('1'),
                        new Dimension\Dimension('2')
                    )
                ),
                [['1920', '100', '1', ['1', '2']]],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(
                        new Dimension\AssetAccount('1920', 'Bank'),
                        new Amount('100'),
                        new Amount('1'),
                        new Dimension\Dimension('1')
                    )
                ),
                [['1920', '100', '1', ['{dim}']]],
            ],
            [
                $verWithAttrFooBar,
                [],
                ['foo' => 'bar']
            ],
            [
                $verWithAttrFoobarFoobar,
                [],
                ['{attr}' => '{attr}']
            ],
        ];
    }

    /**
     * @dataProvider translationsProvider
     * @see setUpBeforeClass
     */
    public function testTranslate(Verification $expected, array $transactions, array $attr = [])
    {
        $template = new Template('', '', ...$transactions);

        foreach ($attr as $name => $value) {
            $template->setAttribute($name, $value);
        }

        $ignoredDate = new \DateTimeImmutable;

        $this->assertEquals(
            $expected->setDate($ignoredDate),
            $template->build(self::$translations, self::$container)->setDate($ignoredDate)
        );
    }

    public function testTranslateDescription()
    {
        $this->assertEquals(
            'One two three',
            (new Template('', 'One {key} three'))->build(['key' => 'two'], new Query)->getDescription(),
            '{key} should be replaced by two'
        );
    }

    public function testExceptionOnMissingTranslation()
    {
        $this->expectException(Exception\RuntimeException::CLASS);
        $template = new Template('', '{not-substituted}');
        $template->build([], new Query);
    }

    public function testTemplateId()
    {
        $this->assertEquals(
            'id',
            (new Template('id', ''))->getTemplateId()
        );
    }

    protected function getObjectToTest()
    {
        return new Template('', '');
    }
}
