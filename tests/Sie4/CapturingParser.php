<?php

declare(strict_types = 1);

namespace byrokrat\accounting\Sie4;

/**
 * Stupid parser that simply captures lexemes
 */
class CapturingParser extends Grammar implements ConsumerInterface
{
    use Helper\CurrencyBuilder;

    /**
     * @var array Content captured during parsing
     */
    private $captured = [];

    private function capture(...$lexemes)
    {
        $this->captured[] = $lexemes;
    }

    public function onUnknown(string $label, array $fields)
    {
        $this->capture("#$label", ...$fields);
    }

    public function onAdress(string $kontakt, string $utdelningsadr, string $postadr, string $tel)
    {
        $this->capture('#ADRESS', $kontakt, $utdelningsadr, $postadr, $tel);
    }

    public function parse($source)
    {
        try {
            parent::parse($source);
            return $this->captured;
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Parsing '".trim($source)."' failed: {$e->getMessage()}");
        }
    }
}
