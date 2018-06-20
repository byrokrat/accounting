<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Exception;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\Logger
 */
class LoggerTest extends \PHPUnit\Framework\TestCase
{
    public function testLogEvents()
    {
        $logger = new Logger;

        $this->assertEmpty($logger->getLog());

        $logger->log('error', 'foo');
        $logger->log('error', 'bar');
        $logger->log('warning', 'foo');
        $logger->log('warning', 'bar');

        $this->assertCount(4, $logger->getLog());

        $logger->resetLog();

        $this->assertEmpty($logger->getLog());
    }

    public function testLineCount()
    {
        $logger = new Logger;

        $logger->resetLog("line A\nline B\nline C");

        $logger->incrementLineCount();

        $logger->log('error', 'foo');

        $logger->incrementLineCount();

        $logger->log('error', 'bar');

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

        $logger->log('error', 'bar');

        $this->assertRegExp(
            "/(1: line D)/",
            $logger->getLog()[0]
        );
    }
}
