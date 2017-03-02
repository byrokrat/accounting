<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Exception;
use Psr\Log\LogLevel;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\Logger
 */
class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testLogEvents()
    {
        $logger = new Logger;

        $this->assertEmpty($logger->getLog());

        $logger->error('foo');
        $logger->error('bar');
        $logger->warning('foo');
        $logger->warning('bar');

        $this->assertCount(4, $logger->getLog());

        $logger->resetLog();

        $this->assertEmpty($logger->getLog());
    }

    public function testLineCount()
    {
        $logger = new Logger;

        $logger->resetLog("line A\nline B\nline C");

        $logger->incrementLineCount();

        $logger->error('foo');

        $logger->incrementLineCount();

        $logger->error('bar');

        $this->assertRegExp(
            "/(1: line A)/",
            $logger->getLog()[0]
        );

        $this->assertRegExp(
            "/(2: line B)/",
            $logger->getLog()[1]
        );

        $logger->resetLog("line D");

        $logger->incrementLineCount();

        $logger->error('bar');

        $this->assertRegExp(
            "/(1: line D)/",
            $logger->getLog()[0]
        );
    }

    public function testIgnoreEvents()
    {
        $logger = new Logger;

        $logger->setLogLevel(LogLevel::ERROR);
        $logger->warning('foobar');
        $this->assertEmpty($logger->getLog());

        $logger->resetLog();

        $logger->setLogLevel(LogLevel::WARNING);
        $logger->notice('bar');
        $this->assertEmpty($logger->getLog());

        $logger->resetLog();

        $logger->setLogLevel(LogLevel::NOTICE);
        $logger->debug('bar');
        $this->assertEmpty($logger->getLog());

        $logger->resetLog();

        $logger->setLogLevel('');
        $logger->error('foo');
        $logger->warning('bar');
        $this->assertEmpty($logger->getLog());
    }
}
