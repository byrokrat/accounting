<?php
declare(strict_types=1);

namespace byrokrat\accounting;

class ReadmeTest extends \hanneskod\readmetester\PHPUnit\ReadmeTestCase
{
    public function testReadmeExamples()
    {
        $this->assertReadme('README.md');
    }
}
