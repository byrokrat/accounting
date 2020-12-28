<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

/**
 * @covers \byrokrat\accounting\Sie4\Parser\Sie4ParserFactory
 */
class Sie4ParserFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateParser()
    {
        $this->assertInstanceOf(
            Sie4Parser::CLASS,
            (new Sie4ParserFactory())->createParser()
        );
    }
}
