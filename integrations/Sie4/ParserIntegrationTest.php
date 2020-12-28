<?php

declare(strict_types=1);

namespace byrokrat\accounting\Sie4\Parser;

use byrokrat\accounting\Container;

/**
 * Validate that all example files in integration/Sie4 can be parsed
 *
 * @see http://www.sie.se/?page_id=125 Example files downloaded 2016-08-22
 */
class Sie4IntegrationTest extends \PHPUnit\Framework\TestCase
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
        list($container, $errors) = $this->parse(
            file_get_contents($fname)
        );

        $this->assertInstanceOf(Container::CLASS, $container);

        if ($errors) {
            $expected = is_readable("$fname.errors") ? file("$fname.errors", FILE_IGNORE_NEW_LINES) : [];

            if ($unexpected = array_diff($errors, $expected)) {
                return $this->markFailure($fname, "Parsing failed due to\n" . implode("\n", $unexpected));
            }

            if ($missing = array_diff($expected, $errors)) {
                return $this->markFailure($fname, "Expected errors missing\n" . implode("\n", $missing));
            }
        }

        if (is_readable("$fname.assertions")) {
            $assertions = include "$fname.assertions";
            $assertions->call($this, $container);
        }
    }

    private function parse(string $content): array
    {
        $parser = (new Sie4ParserFactory())->createParser();

        return [$parser->parse($content), $parser->getErrorLog()];
    }

    private function markFailure(string $fname, string $msg)
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
