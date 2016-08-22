<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Exception;

class ParserExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorsAndWarnings()
    {
        $exception = new ParserException(
            $errors = ['A', 'B'],
            $warnings = ['C', 'D']
        );

        $this->assertSame(
            $errors,
            $exception->getErrors()
        );

        $this->assertSame(
            $warnings,
            $exception->getWarnings()
        );
    }
}
