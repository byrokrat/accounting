<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    use utils\InterfaceAssertionsTrait;

    public function testAttributable()
    {
        $this->assertAttributable(new Container);
    }

    public function testItems()
    {
        $container = new Container(
            $a = 'foo',
            $b = 'bar'
        );

        $container->addItems(
            $c = 'baz',
            $d = 'qux'
        );

        $this->assertSame(
            [$a, $b, $c, $d],
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
                (new Container)->addItems($a, $b)
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
            (new Container($a, $b))->query()->toArray()
        );
    }
}
