<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Exception;

class ParserExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorsAndWarnings()
    {
        $exception = new ParserException(
            $log = [
                'error' => ['A', 'B'],
                'warning' => ['C', 'D']
            ]
        );

        $this->assertSame(
            $log,
            $exception->getLog()
        );

        $this->assertInternalType(
            'string',
            $exception->getMessage()
        );
    }
}
