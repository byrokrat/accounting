<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Exception\LogicException;

class VerificationTemplateTest extends \PHPUnit\Framework\TestCase
{
    public function testVerificationDefaultValues()
    {
        $defaults = (new VerificationTemplate([]))->getValues();
        $this->assertArrayHasKey('id', $defaults);
        $this->assertArrayHasKey('transaction_date', $defaults);
        $this->assertArrayHasKey('registration_date', $defaults);
        $this->assertArrayHasKey('description', $defaults);
        $this->assertArrayHasKey('signature', $defaults);
        $this->assertArrayHasKey('transactions', $defaults);
    }

    public function testTransactionDefaults()
    {
        $defaults = (new VerificationTemplate(['transactions' => [0 => []]]))->getValues()['transactions'][0];
        $this->assertArrayHasKey('transaction_date', $defaults);
        $this->assertArrayHasKey('description', $defaults);
        $this->assertArrayHasKey('signature', $defaults);
        $this->assertArrayHasKey('amount', $defaults);
        $this->assertArrayHasKey('quantity', $defaults);
        $this->assertArrayHasKey('account', $defaults);
        $this->assertArrayHasKey('dimensions', $defaults);
    }

    public function testExceptionOnNonStringValue()
    {
        $this->expectException(LogicException::CLASS);
        new VerificationTemplate(['id' => null]);
    }

    public function testExceptionOnNonArrayValue()
    {
        $this->expectException(LogicException::CLASS);
        new VerificationTemplate(['transactions' => 'this-is-not-an-array']);
    }

    public function testExceptionOnMissformedTransaction()
    {
        $this->expectException(LogicException::CLASS);
        new VerificationTemplate(['transactions' => ['this-should-be-an-inner-array']]);
    }

    public function testExceptionOnNonStringValueInTransaction()
    {
        $this->expectException(LogicException::CLASS);
        new VerificationTemplate(['transactions' => [['id' => null]]]);
    }
}
