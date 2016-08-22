<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4\Helper;

use byrokrat\accounting\utils\PropheciesTrait;
use byrokrat\accounting\Exception;

/**
 * @covers \byrokrat\accounting\Sie4\Helper\ErrorHelper
 */
class ErrorHelperTest extends \PHPUnit_Framework_TestCase
{
    use PropheciesTrait;

    /**
     * @var Object Created in setup()
     */
    private $errorHelper;

    public function setup()
    {
        $this->errorHelper = $this->getMockForTrait(ErrorHelper::CLASS);
    }

    public function testRegisterErrors()
    {
        $this->assertEmpty($this->errorHelper->getErrors());

        $this->errorHelper->registerError('foo');
        $this->errorHelper->registerError('bar');

        $this->assertSame(
            ['foo', 'bar'],
            $this->errorHelper->getErrors()
        );

        $this->errorHelper->resetErrorState();

        $this->assertEmpty($this->errorHelper->getErrors());
    }

    public function testRegisterWarnings()
    {
        $this->assertEmpty($this->errorHelper->getWarnings());

        $this->errorHelper->registerWarning('foo');
        $this->errorHelper->registerWarning('bar');

        $this->assertSame(
            ['foo', 'bar'],
            $this->errorHelper->getWarnings()
        );

        $this->errorHelper->resetErrorState();

        $this->assertEmpty($this->errorHelper->getWarnings());
    }

    public function testNoErrorReporting()
    {
        $this->errorHelper->setErrorLevel(E_ERROR);
        $this->errorHelper->registerWarning('bar');
        $this->assertNull($this->errorHelper->validateErrorState());

        $this->errorHelper->resetErrorState();

        $this->errorHelper->setErrorLevel(E_WARNING);
        $this->errorHelper->registerError('bar');
        $this->assertNull($this->errorHelper->validateErrorState());

        $this->errorHelper->resetErrorState();

        $this->errorHelper->setErrorLevel(0);
        $this->errorHelper->registerError('foo');
        $this->errorHelper->registerWarning('bar');
        $this->assertNull($this->errorHelper->validateErrorState());
    }

    public function testErrorReportingOnError()
    {
        $this->errorHelper->setErrorLevel(E_ERROR);
        $this->errorHelper->registerError('bar');
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->errorHelper->validateErrorState();
    }

    public function testErrorReportingOnWarning()
    {
        $this->errorHelper->setErrorLevel(E_WARNING);
        $this->errorHelper->registerWarning('bar');
        $this->setExpectedException(Exception\ParserException::CLASS);
        $this->errorHelper->validateErrorState();
    }

    public function testUnknownLabelResultsInWarning()
    {
        $this->errorHelper->onUnknown('label', ['foo', 'bar']);

        $this->assertSame(
            ['Encountered unknown statement: #label "foo" "bar"'],
            $this->errorHelper->getWarnings()
        );
    }
}
