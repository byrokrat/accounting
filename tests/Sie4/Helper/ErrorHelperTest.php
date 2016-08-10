<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\utils\PropheciesTrait;
use byrokrat\accounting\Account;

/**
 * @covers byrokrat\accounting\Sie4\Helper\ErrorHelper
 */
class ErrorHelperTest extends \PHPUnit_Framework_TestCase
{
    use PropheciesTrait;

    public function testRegisterError()
    {
        $errorHelper = $this->getMockForTrait(ErrorHelper::CLASS);

        $errorHelper->registerError('foo');
        $errorHelper->registerError('bar');

        $this->assertSame(
            ['foo', 'bar'],
            $errorHelper->getErrors()
        );
    }
}
