<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class TemplateTest extends BaseTestCase
{
    public function testGetters()
    {
        $template = new Template('name', 'text');
        $this->assertEquals('name', $template->getName());
        $this->assertEquals('text', $template->getText());
    }

    public function testSubstituteText()
    {
        $template = new Template('', 'One {key} three');
        $template->substitute(['key' => 'two']);
        $this->assertEquals(
            'One two three',
            $template->getText(),
            '{key} should be replaced by two'
        );
    }

    public function testSubstituteTransactions()
    {
        $template = new Template('', '');
        $template->addRawTransaction('{in}', '-400');
        $template->addRawTransaction('1920', '{amount}');

        $template->substitute(
            [
                'in' => '1920',
                'amount' => '400'
            ]
        );

        $expectedTransactions = [
            ['1920', '-400'],
            ['1920', '400']
        ];

        $this->assertEquals(
            $expectedTransactions,
            $template->getRawTransactions()
        );
    }

    public function testExceptionOnMissingSubstitutionNumber()
    {
        $this->setExpectedException(Exception\UnexpectedValueException::CLASS);
        $template = new Template('', '');
        $template->addRawTransaction('{in}', '-400');
        $template->buildVerification($this->prophesize(Query::CLASS)->reveal());
    }

    public function testExceptionOnMissingSubstitutionAmount()
    {
        $this->setExpectedException(Exception\UnexpectedValueException::CLASS);
        $template = new Template('', '');
        $template->addRawTransaction('1920', '{amount}');
        $template->buildVerification($this->prophesize(Query::CLASS)->reveal());
    }

    public function testBuildVerification()
    {
        $template = new Template('', '');
        $template->addRawTransaction('1920', '450');
        $template->addRawTransaction('3000', '-450');

        $accounts = new Query([
            new Account\Asset(1920, 'Bank'),
            new Account\Earning(3000, 'Incomes')
        ]);

        $expectedTransactions = [
            new Transaction(new Account\Asset(1920, 'Bank'), new Amount('450')),
            new Transaction(new Account\Earning(3000, 'Incomes'), new Amount('-450')),
        ];

        $this->assertEquals(
            $expectedTransactions,
            $template->buildVerification($accounts)->getTransactions()
        );
    }
}
