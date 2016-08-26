# TODO

## Transaktioner

* En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
* Här ingår även stöd för BTRANS och RTRANS till sie4

## Huvudbok

Består av summeringar för varje konto:

* ingående balans (vid årets början, från "ingående balans")
* ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
* poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning)
* utgående saldo
* varje transaktion listas och behöver veta villket verifikat det tillhör..

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
