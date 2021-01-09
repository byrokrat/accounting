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

    public function testGetId()
    {
        $this->assertIsString((new Container())->getId());
    }

    public function testGetItems()
    {
        $container = new Container(
            $a = $this->createMock(AccountingObjectInterface::class),
            $b = $this->createMock(AccountingObjectInterface::class),
        );

        $this->assertSame(
            [$a, $b],
            $container->getItems()
        );
    }

    public function testQueryable()
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
            $b = $this->createMock(AccountingObjectInterface::class),
        );

        $outer = new Container(
            $a = $this->createMock(AccountingObjectInterface::class),
            $inner
        );

        $this->assertSame(
            [$a, $inner, $b],
            $outer->select()->asArray()
        );
    }
}
