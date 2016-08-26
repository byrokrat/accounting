<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

/**
 * @covers \byrokrat\accounting\Template
 */
class TemplateTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait;

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
            new Account\Asset('1920', 'Bank'),
            new Account\Earning('3000', 'Incomes'),
            new Dimension('1'),
            new Dimension('2')
        ]);
    }

    public function translationsProvider()
    {
        return [
            [
                (new Verification)->addTransaction(
                    new Transaction(new Account\Earning('3000', 'Incomes'), new Amount('-400'))
                ),
                [['{in}', '-400']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(new Account\Asset('1920', 'Bank'), new Amount('400'), 0)
                ),
                [['1920', '{amount}']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100'), 10)
                ),
                [['1920', '100', '{quantity}']],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(
                        new Account\Asset('1920', 'Bank'),
                        new Amount('100'),
                        1,
                        new Dimension('1'),
                        new Dimension('2')
                    )
                ),
                [['1920', '100', '1', ['1', '2']]],
            ],
            [
                (new Verification)->addTransaction(
                    new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100'), 1, new Dimension('1'))
                ),
                [['1920', '100', '1', ['{dim}']]],
            ],
            [
                (new Verification)->setAttribute('foo', 'bar'),
                [],
                ['foo' => 'bar']
            ],
            [
                (new Verification)->setAttribute('foobar', 'foobar'),
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

        $ignoredDate = new \DateTime;

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
        $this->setExpectedException(Exception\RuntimeException::CLASS);
        $template = new Template('', '{not-substituted}');
        $template->build([], new Query);
    }

    public function testAttributable()
    {
        $this->assertAttributable(new Template('', ''));
    }

    public function testDescribable()
    {
        $this->assertDescribable(
            'description',
            new Template('', 'description')
        );
    }

    public function testTemplateId()
    {
        $this->assertEquals(
            'id',
            (new Template('id', ''))->getTemplateId()
        );
    }
}
