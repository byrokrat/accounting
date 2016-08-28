<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\integration;

use byrokrat\accounting\Sie4\ParserFactory;
use byrokrat\accounting\Exception;

/**
 * Validate that all example files in integration/files can be parsed
 *
 * @see http://www.sie.se/?page_id=125 Example files downloaded 2016-08-22
 *
 * @covers \byrokrat\accounting\Sie4\Grammar
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser Generated in setUpBeforeClass()
     */
    private static $parser;

    public static function setUpBeforeClass()
    {
        self::$parser = (new ParserFactory)->createParser();
    }

    public function fileInfoProvider()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/files') as $fileInfo) {
            if (!in_array(strtoupper($fileInfo->getExtension()), ['SE', 'SI'])) {
                continue;
            }

            yield [$fileInfo->getRealPath()];
        }
    }

    /**
     * @dataProvider fileInfoProvider
     * @group slow
     */
    public function testFiles(string $fname)
    {
        try {
            self::$parser->parse(file_get_contents($fname));
        } catch (Exception\ParserException $e) {
            $errorsFname = "$fname.errors";
            $allowedErrors = is_readable($errorsFname) ? file($errorsFname, FILE_IGNORE_NEW_LINES) : [];
            if ($unexpectedErrors = array_diff($e->getLog(), $allowedErrors)) {
                return $this->failure($fname, "Parsing failed due to\n" . implode("\n", $unexpectedErrors));
            }
        } catch (\Exception $e) {
            return $this->failure($fname, $e->getMessage());
        }

        $assertionsFname = "$fname.assertions";

        if (is_readable($assertionsFname)) {
            $assertions = include $assertionsFname;
            $assertions->call($this, self::$parser->getContainer());
        }
    }

    private function failure(string $fname, string $msg)
    {
        $this->fail(
            sprintf(
                "[%s] %s \n\nFor more information try\nbin/check_sie_file \"%s\"",
                basename($fname),
                $msg,
                $fname
            )
        );
    }
}
