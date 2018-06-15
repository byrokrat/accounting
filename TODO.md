# TODO

* @return phpstan statements till getIterator på alla ställen där det går..
* kolla igenom alla yields så att jag använder yield from vid arrayer...

* toString-grejjen är jag väldigt tveksom över också...

* skippa allt det här med refs från transaction till verification

* Arbeta bort så många av Helper som möjligt..
    (+ test traits...)
    Signature kan returnera '' om ingen finns satt
    Samma med description såklart..

* Kolla av vanliga TODOs i kod..

## VerificationBuilder

* Vill vi ha en Verification namespace?
    - Fundera på det...
    - Att registrationDate kan vara transactionDate ska ställas in i VerificationBuilder
        liksom allt annat som kan vara speciellt på något sätt...

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
