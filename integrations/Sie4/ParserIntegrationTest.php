<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Container;

/**
 * Validate that all example files in integration/Sie4 can be parsed
 *
 * @see http://www.sie.se/?page_id=125 Example files downloaded 2016-08-22
 */
class ParserIntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function fileInfoProvider()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/parserdata') as $fileInfo) {
            if (in_array(strtoupper($fileInfo->getExtension()), ['SE', 'SI'])) {
                yield [$fileInfo->getRealPath()];
            }
        }
    }

    /**
     * @dataProvider fileInfoProvider
     */
    public function testFiles(string $fname)
    {
        $parser = new Sie4Parser();

        $container = $parser->parse(file_get_contents($fname));

        $this->assertInstanceOf(Container::class, $container);

        if (is_readable("$fname.assertions")) {
            $assertions = include "$fname.assertions";
            $assertions->call($this, $container);
        }
    }
}
