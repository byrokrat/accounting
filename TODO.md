# TODO

## Template

Bugg: Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK
eller annan inställd valuta.

Där är också dålig kod med skumma array strukturer som behöver utvecklas...

Template skriver inte heller något datum till varken transaction eller verifikat.

## Parser

Se över alla setAttribute() i Grammar. Jag behöver en bra och konsekvent namn-strategi!
Se exempelvis 'incoming_balance'

## Transaktioner

1. En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
1. Här ingår även stöd för BTRANS och RTRANS till sie4

## Processor

Ska räkna ihop även quantity på samma sätt som amounts..
Behöver kunna presenteras på samma sätt som annat...

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
