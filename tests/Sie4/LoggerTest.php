<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

use byrokrat\accounting\Exception;
use Psr\Log\LogLevel;

/**
 * @covers \byrokrat\accounting\Sie4\Logger
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testLogEvents()
    {
        $logger = new Logger;

        $this->assertEmpty($logger->getLog());

        $logger->error('foo');
        $logger->error('bar');
        $logger->warning('foo');
        $logger->warning('bar');

        $this->assertSame(
            [
                'error' => ['foo', 'bar'],
                'warning' => ['foo', 'bar'],
            ],
            $logger->getLog()
        );

        $logger->resetLog();

        $this->assertEmpty($logger->getLog());
    }

    public function testIgnoreEvents()
    {
        $logger = new Logger;

        $logger->setLogLevel(LogLevel::ERROR);
        $logger->warning('foobar');
        $this->assertNull($logger->validateState());

        $logger->resetLog();

        $logger->setLogLevel(LogLevel::WARNING);
        $logger->notice('bar');
        $this->assertNull($logger->validateState());

        $logger->resetLog();

        $logger->setLogLevel(LogLevel::NOTICE);
        $logger->debug('bar');
        $this->assertNull($logger->validateState());

        $logger->resetLog();

        $logger->setLogLevel('');
        $logger->error('foo');
        $logger->warning('bar');
        $this->assertNull($logger->validateState());
    }

    public function testErrorReporting()
    {
        $logger = new Logger;
        $logger->error('bar');
        $this->setExpectedException(Exception\ParserException::CLASS);
        $logger->validateState();
    }
}
