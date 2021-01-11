<?php

declare(strict_types=1);

namespace byrokrat\accounting;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Container();
    }

    public function testSimpleSelect()
    {
        $container = new Container(
            $a = $this->createMock(AccountingObjectInterface::class),
            $b = $this->createMock(AccountingObjectInterface::class),
        );

        $this->assertSame(
            [$a, $b],
            $container->select()->asArray()
        );
    }

    public function testNestingContainers()
    {
        $inner = new Container(
            $c = $this->createMock(AccountingObjectInterface::class),
        );

        $middle = new Container(
            $b = $this->createMock(AccountingObjectInterface::class),
            $inner
        );

        $outer = new Container(
            $a = $this->createMock(AccountingObjectInterface::class),
            $middle
        );

        $this->assertSame(
            [$a, $b, $c],
            $outer->select()->asArray()
        );
    }
}
