<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\integration;

use byrokrat\accounting\Sie4\Parser;

/**
 * Validate that all example files in integration/files can be parsed
 *
 * @see http://www.sie.se/?page_id=125 Example files downloaded 2016-08-22
 * @coversNothing
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser Generated in setUpBeforeClass()
     */
    private static $parser;

    public static function setUpBeforeClass()
    {
        self::$parser = new Parser;
    }

    public function filesProvider()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/files') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            yield [$fileInfo->getFilename(), file_get_contents($fileInfo->getRealPath())];
        }
    }

    /**
     * @dataProvider filesProvider
     */
    public function testFiles(string $filename, string $content)
    {
        try {
            self::$parser->parse($content);
        } catch (\Exception $e) {
            $this->fail("Parsing >$filename< failed with exception >{$e->getMessage()}<");
        }
    }
}
