<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

trait DescriptionTestsTrait
{
    abstract protected function getObjectToTest();

    public function testDescription()
    {
        $obj = $this->getObjectToTest();

        $this->assertSame('', $obj->getDescription());

        $description = 'foobar';

        $obj->setDescription($description);

        $this->assertSame($description, $obj->getDescription());
    }
}
