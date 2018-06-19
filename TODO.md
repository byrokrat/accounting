# TODO

* Att generera huvudbok
    - har skrivit ett nytt förslag i docs/01-querying.md
    - validera att detta funkar med parsed content... (ver id..)
    - flytt exempel till egen fil under docs/ så att det blir enkelt att hitta...

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

* Inte alls up to scratch...
* Behövs Settings? Kanske räcker med en attributable...
* Add a default return value for getAttribute() ??
    kan bla användas i TransactionProcessor...
