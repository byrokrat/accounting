<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\integration;

use byrokrat\accounting\Sie4\SieParserFactory;
use byrokrat\accounting\Exception;

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
        self::$parser = (new SieParserFactory)->createParser();
    }

    public function filesProvider()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/files') as $fileInfo) {
            if (!in_array(strtoupper($fileInfo->getExtension()), ['SE', 'SI'])) {
                continue;
            }

            $fname = $fileInfo->getRealPath();

            yield [
                $fileInfo->getFilename(),
                file_get_contents($fileInfo->getRealPath()),
                is_readable("$fname.errors") ? file("$fname.errors", FILE_IGNORE_NEW_LINES) : [],
                is_readable("$fname.assertions") ? include "$fname.assertions" : null
            ];
        }
    }

    /**
     * @dataProvider filesProvider
     * @group slow
     */
    public function testFiles(string $filename, string $content, array $expectedErrors, \Closure $assertions = null)
    {
        try {
            self::$parser->parse($content);
        } catch (Exception\ParserException $e) {
            if ($unexpectedErrors = array_diff($e->getLog(), $expectedErrors)) {
                return $this->fail("[$filename] Parsing failed due to\n" . implode("\n", $unexpectedErrors));
            }
        } catch (\Exception $e) {
            return $this->fail("[$filename] {$e->getMessage()}");
        }

        if ($assertions instanceof \Closure) {
            $assertions->call($this, self::$parser->getContainer());
        }
    }
}
