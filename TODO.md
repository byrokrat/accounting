# TODO

## Transaktioner

1. En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
1. Här ingår även stöd för BTRANS och RTRANS till sie4

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
