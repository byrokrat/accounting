<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    use utils\AttributableTestsTrait;

    protected function getObjectToTest()
    {
        return new Container;
    }

    public function testItems()
    {
        $container = new Container(
            $a = 'foo',
            $b = 'bar'
        );

        $container->addItem($c = 'baz');

        $this->assertSame(
            [$a, $b, $c],
            $container->getItems()
        );
    }

    public function testIterable()
    {
        $this->assertSame(
            [
                $a = 'foo',
                $b = 'bar'
            ],
            iterator_to_array(
                new Container($a, $b)
            )
        );
    }

    public function testQueryable()
    {
        $this->assertSame(
            [
                $a = 'foo',
                $b = 'bar'
            ],
            (new Container($a, $b))->select()->asArray()
        );
    }

    public function testAddItems()
    {
        $this->assertSame(
            ['foo', 'bar', 'baz'],
            (new Container('foo'))->addItems(['bar', 'baz'])->getItems()
        );
    }
}
