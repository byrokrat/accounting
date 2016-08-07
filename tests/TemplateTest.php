<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use byrokrat\amount\Amount;

class TemplateTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait;

    public function testGetId()
    {
        $this->assertEquals(
            'id',
            (new Template('id', ''))->getTemplateId()
        );
    }

    public function testDescribable()
    {
        $this->assertDescribable(
            'description',
            new Template('', 'description')
        );
    }

    public function testSubstituteDescription()
    {
        $template = new Template('', 'One {key} three');
        $template->substitute(['key' => 'two']);

        $this->assertEquals(
            'One two three',
            $template->getDescription(),
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

        $this->assertEquals(
            [
                ['1920', '-400'],
                ['1920', '400']
            ],
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

        $template->setAttribute('foo', 'bar');

        $verification = $template->buildVerification(new Query([
            new Account\Asset(1920, 'Bank'),
            new Account\Earning(3000, 'Incomes')
        ]));

        $this->assertEquals(
            [
                new Transaction(new Account\Asset(1920, 'Bank'), new Amount('450')),
                new Transaction(new Account\Earning(3000, 'Incomes'), new Amount('-450')),
            ],
            $verification->getTransactions()
        );

        return $verification;
    }

    public function testAttributable()
    {
        $this->assertAttributable(new Template('', ''));
    }

    /**
     * @depends testBuildVerification
     */
    public function testAttributesWrittenToVerification(Verification $verification)
    {
        $this->assertEquals(
            'bar',
            $verification->getAttribute('foo')
        );
    }
}
