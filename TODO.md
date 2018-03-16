# TODO

## Klass-struktur

* `TransactionInterface`, och flytta klasser till `Transaction` namespace
* `DimensionInterface` och Account mf till `Dimension` namespace.
* Även `AccountInterface`??
* Rename `Summary` => `Balance` (glöm inte getSummary)
* Interfaces should not be in an `Interfaces` namespace, this is just silly..
* phpstan...

## Cleanup repo

1. `bin` kan gå bort
1. `res` kan gå bort
1. `integrations` ska antagligen heta någonting annat...

## Parser

Att köra print_r på $contet->getAttributes() efter parse visar en hel del konstigheter
i hur attributes skrivits. Se om det är något jag kan rätta till...

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.
