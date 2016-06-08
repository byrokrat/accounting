<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * Assertions for testing the SIE grammar
 */
abstract class GrammarTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Parse SIE content and return an array of captured lexemes
     */
    public function parseSie($source, &$error = ''): array
    {
        try {
            return (new CapturingParser)->parse($source);
        } catch (\InvalidArgumentException $e) {
            $error = $e->getMessage();
            return [];
        }
    }

    /**
     * Assert that $source contains valid SIE data
     */
    public function assertSieSyntax($source, $message = '')
    {
        $this->parseSie($source, $error);
        $this->assertEmpty($error, "$message\n$error");
    }

    /**
     * Assert that $source contains invalid SIE data
     */
    public function assertInvalidSieSyntax($source, $message = '')
    {
        $this->parseSie($source, $error);
        $this->assertNotEmpty($error, $message);
    }

    /**
     * Assert that $lexeme is a valid SIE lexeme found in $source
     */
    public function assertSieLexeme($lexeme, $source, $message = '')
    {
        $this->assertSieSyntax($source, $message);
        $this->assertContains(
            $lexeme,
            $this->parseSie($source)[0],
            $message ?: "Failed asserting that lexeme '$lexeme' is found in '$source'"
        );
    }

    /**
     * Assert that the complete list of captured lexemes equals $lexemes
     */
    public function assertSieLexemes(array $lexemes, $source, $message = '')
    {
        $this->assertSieSyntax($source, $message);
        $this->assertEquals(
            $lexemes,
            $this->parseSie($source),
            $message ?: "Failed asserting lexemes found in '$source'"
        );
    }
}
