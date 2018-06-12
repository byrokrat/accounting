# TODO

* Kolla av vanliga TODOs i kod..

## Verification

1. Ska ta värden till construct
1. Bort med setters
1. Ej return self

## Parser

Att köra print_r på $contet->getAttributes() efter parse visar en hel del konstigheter
i hur attributes skrivits. Se om det är något jag kan rätta till...

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
   Finns CurrencyFactory i parser som kanske kan användas..
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.

## Writer

Inte alls up to scratch...
