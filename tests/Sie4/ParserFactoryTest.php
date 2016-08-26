<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * @covers \byrokrat\accounting\Sie4\ParserFactory
 */
class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateParser()
    {
        $this->assertInstanceOf(
            Parser::CLASS,
            (new ParserFactory)->createParser()
        );
    }
}
