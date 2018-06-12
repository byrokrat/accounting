<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\AttributableInterface;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Dateable;
use byrokrat\accounting\Interfaces\Signable;
use byrokrat\accounting\Exception\LogicException;

trait InterfaceAssertionsTrait
{
    /**
     * Assert that attributes are set on attributable
     */
    public function assertAttributes(array $expectedAttr, AttributableInterface $attributable)
    {
        foreach ($expectedAttr as $key => $value) {
            $this->assertEquals(
                $value,
                $attributable->getAttribute($key),
                "Failed asserting that attributable contains attribute $key equal to " . var_export($value, true)
            );
        }
    }

    /**
     * Assert the behaviour of the Describable implementation
     */
    public function assertDescribable(string $expected, Describable $describable)
    {
        $this->assertSame(
            $expected,
            $describable->getDescription()
        );

        $this->assertSame(
            $newDescription = '____foobarbaz____',
            $describable->setDescription($newDescription)->getDescription(),
            'The correct description should be returned'
        );

        $describable->setDescription($expected);
    }

    /**
     * Assert the behaviour of the Dateable implementation
     */
    public function assertDateable(\DateTimeImmutable $expected, Dateable $dateable)
    {
        $this->assertTrue($dateable->hasDate());

        $this->assertSame(
            $expected,
            $dateable->getDate()
        );

        $newDate = new \DateTimeImmutable;

        $dateable->setDate($newDate);

        $this->assertSame(
            $newDate,
            $dateable->getDate()
        );

        $dateable->setDate($expected);
    }

    /**
     * Assert the behaviour of the Dateable implementation when date is not set
     */
    public function assertDateableDateNotSet(Dateable $dateable)
    {
        $this->assertFalse(
            $dateable->hasDate(),
            'Dateable should not have a date set when using assertDateableDateNotSet'
        );

        $exceptionThrown = false;

        try {
            $dateable->getDate();
        } catch (LogicException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue(
            $exceptionThrown,
            'Calling getDste when date is not set should throw a LogicException'
        );
    }

    /**
     * Assert the behaviour of the Signable implementation
     */
    public function assertSignable(string $expected, Signable $signable)
    {
        $this->assertTrue($signable->hasSignature());

        $this->assertSame(
            $expected,
            $signable->getSignature()
        );

        $newSignature = '____foobarbaz____';

        $signable->setSignature($newSignature);

        $this->assertSame(
            $newSignature,
            $signable->getSignature()
        );

        $signable->setSignature($expected);
    }

    /**
     * Assert the behaviour of the Signable implementation when signature is not set
     */
    public function assertSignableSignatureNotSet(Signable $signable)
    {
        $this->assertFalse(
            $signable->hasSignature(),
            'Signable should not have a signature set when using assertSignableSignatureNotSet'
        );

        $exceptionThrown = false;

        try {
            $signable->getSignature();
        } catch (LogicException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue(
            $exceptionThrown,
            'Calling getSignature when signature is not set should throw a LogicException'
        );
    }
}
