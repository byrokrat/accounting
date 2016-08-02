<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\BaseTestCase;
use byrokrat\accounting\Account;

/**
 * @covers byrokrat\accounting\Sie4\Helper\ErrorHelper
 */
class ErrorHelperTest extends BaseTestCase
{
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
