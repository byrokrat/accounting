# TODO

* skriv id till transaction i parser...
    det löser problemet med att vi inte kan göra en propper huvudbok just nu då ver_num ej är access..
    skriv detta huvudbok exempel i egen fil under docs/ så att det blir enkelt att hitta...
    se nu docs/01-querying

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
   Finns CurrencyFactory i parser som kanske kan användas..
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.
1. Uppdatera readme och dokumentation

## VerificationBuilder

* Vill vi ha en Verification namespace?
    - Eller ska Template i praktiken fungera som vår builder??
    - Att registrationDate kan vara transactionDate ska ställas in i VerificationBuilder
        liksom allt annat som kan vara speciellt på något sätt...

## Parser

* Att köra print_r på $contet->getAttributes() efter parse visar en hel del konstigheter
  i hur attributes skrivits. Se om det är något jag kan rätta till...
* Drop PSR/log. Skriv detta på något eget sätt i stället...

## Writer

Inte alls up to scratch...
