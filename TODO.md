# TODO

## Klass-struktur

* Interfaces should not be in an `Interfaces` namespace, this is just silly..
* Ta bort så många som möjligt av hasDate() osv från Helpers...

* Rename `Summary` => `Balance` (glöm inte getSummary)

* TODOs i kod...

## Cleanup repo

1. `integrations` ska antagligen heta någonting annat...

## Parser

Att köra print_r på $contet->getAttributes() efter parse visar en hel del konstigheter
i hur attributes skrivits. Se om det är något jag kan rätta till...

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
   Finns CurrencyFactory i parser som kanske kan användas..
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.
1. Phpstan errors (sätt failOnError igen i bob när allt är fixat..)

## Writer

Inte alls up to scratch...
