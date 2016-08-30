<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

use hanneskod\readmetester\PHPUnit\AssertReadme;

/**
 * @coversNothing
 */
class DocumentationExamples extends \PHPUnit_Framework_TestCase
{
    public function testDocumentationExamples()
    {
        if (!class_exists(AssertReadme::CLASS)) {
            return $this->markTestSkipped('Readme-tester is not available.');
        }

        $asserter = new AssertReadme($this);

        $asserter->assertReadme('README.md');
        $asserter->assertReadme('docs/01-querying.md');
        $asserter->assertReadme('docs/02-sie.md');
        $asserter->assertReadme('docs/03-templates.md');
    }
}
