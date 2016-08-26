<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Exception;

class ParserExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorsAndWarnings()
    {
        $log = [
            'message A',
            'message B'
        ];

        $exception = new ParserException($log);

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
