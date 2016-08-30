<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Writer;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    public function escapeStringProvider()
    {
        return [
            ["bar", '"bar"'],
            ["\n", '""'],
            ["'", '"\\\'"'],
        ];
    }

    /**
     * @dataProvider escapeStringProvider
     */
    public function testEscapeString($raw, $escaped)
    {
        $this->assertSame(
            $escaped,
            (new Output)->escape($raw)
        );
    }

    public function writeProvider()
    {
        return [
            ['#ID %s %s', ['foo', 'bar'], '#ID "foo" "bar"'],
        ];
    }

    /**
     * @dataProvider writeProvider
     */
    public function testWrite($format, $args, $expected)
    {
        $output = new Output;
        $output->write($format, ...$args);
        $this->assertSame(
            $expected,
            $output->getContent()
        );
    }

    /**
     * @dataProvider writeProvider
     */
    public function testWriteln($format, $args, $expected)
    {
        $output = new Output;
        $output->writeln($format, ...$args);
        $this->assertSame(
            $expected . Output::EOL,
            $output->getContent()
        );
    }

    public function testCharsetConversion()
    {
        $output = new Output;
        $output->write('åäö');
        $this->assertSame(
            iconv("UTF-8", "CP437", 'åäö'),
            $output->getContent()
        );
    }
}
