# TODO

1. Support `__set_state` på alla value-classer, så att en kan göra
   var_export på ett parse-tree och spara för senare analys...
1. skapa en run_tests.sh som kör alla tester och ska köras innan
   jag committar någonting... (kolla travis och liknande...)

## Verifikationer

1. Måste kunna hantera verifikationer utan transaktioner (kommer ex från JFSBok)

## Transaktioner

1. En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
1. Här ingår även stöd för BTRANS och RTRANS till sie4

## Huvudbok

Består av summeringar för varje konto:

1. ingående balans (vid årets början, från "ingående balans")
1. ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
1. poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning)
1. utgående saldo
1. varje transaktion listas och behöver veta villket verifikat det tillhör..

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
