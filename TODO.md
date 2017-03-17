# TODO

## Parser

Se över alla setAttribute() i Grammar. Jag behöver en bra och konsekvent namn-strategi!
Se exempelvis 'incoming_balance'

## Transaktioner

1. En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
1. Här ingår även stöd för BTRANS och RTRANS till sie4

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
