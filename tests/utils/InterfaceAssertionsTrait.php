<?php

declare(strict_types = 1);

namespace byrokrat\accounting\utils;

use byrokrat\accounting\Interfaces\Attributable;
use byrokrat\accounting\Interfaces\Describable;
use byrokrat\accounting\Interfaces\Dateable;
use byrokrat\accounting\Interfaces\Signable;
use byrokrat\accounting\Exception\LogicException;

trait InterfaceAssertionsTrait
{
    abstract public function assertSame($expected, $actual, $message = '');

    abstract public function assertTrue($expected, $message = '');

    abstract public function assertFalse($expected, $message = '');

    /**
     * Assert the behaviour of the Attributable implementation
     */
    public function assertAttributable(Attributable $attributable)
    {
        $key = '___attribute-assumed-not-to-be-set___';
        $value = 'foobarbaz';

        $this->assertFalse($attributable->hasAttribute($key));

        $attributable->setAttribute($key, $value);

        $this->assertTrue($attributable->hasAttribute($key));

        $this->assertSame(
            $value,
            $attributable->getAttribute($key),
            'Getting a set attribute should return it'
        );

        $this->assertSame(
            $value,
            $attributable->getAttribute(str_replace('a', 'A', $key)),
            'Getting a set attribute should base case-insensitive'
        );

        $this->assertSame(
            $value,
            $attributable->getAttributes()[$key],
            'Getting all attributes should return attribute i small case'
        );

        $exceptionThrown = false;

        try {
            $attributable->getAttribute('___assumed-not-to-exist___');
        } catch (LogicException $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue(
            $exceptionThrown,
            'Reading a non-existing attribute should throw a LogicException'
        );
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
    public function assertDateable(\DateTimeInterface $expected, Dateable $dateable)
    {
        $this->assertTrue($dateable->hasDate());

        $this->assertSame(
            $expected,
            $dateable->getDate()
        );

        $newDate = new \DateTime;

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
