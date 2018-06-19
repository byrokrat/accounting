<?php

declare(strict_types = 1);

namespace byrokrat\accounting;

class ContainerTest extends \PHPUnit\Framework\TestCase
{
    use AttributableTestTrait;

    protected function getAttributableToTest(): AttributableInterface
    {
        return new Container;
    }

    public function testGetItems()
    {
        $container = new Container(
            $a = 'foo',
            $b = 'bar'
        );

        $this->assertSame(
            [$a, $b],
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
}
