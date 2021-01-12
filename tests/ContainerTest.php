<?php

declare(strict_types=1);

namespace byrokrat\accounting;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes()
    {
        $attributable = new Container();

        $this->assertFalse($attributable->hasAttribute('does-not-exist'));

        $this->assertSame('', $attributable->getAttribute('does-not-exist'));

        $attributable->setAttribute('foo', 'bar');

        $this->assertTrue($attributable->hasAttribute('foo'));

        $this->assertSame('bar', $attributable->getAttribute('foo'));

        $this->assertSame(['foo' => 'bar'], $attributable->getAttributes());
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
