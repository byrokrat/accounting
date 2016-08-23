<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * @covers \byrokrat\accounting\Sie4\SieParserFactory
 */
class SieParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateParser()
    {
        $this->assertInstanceOf(
            SieParser::CLASS,
            (new SieParserFactory)->createParser()
        );
    }
}
