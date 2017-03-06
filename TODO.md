# TODO

1. Support `__set_state` på alla value-classer, så att en kan göra
   var_export på ett parse-tree och spara för senare analys...

## Readme

1. Låt oss först här generera lite verifikationer med templates.
1. Och sedan analysera de genererade verifikationerna...
1. Och så kan jag passa på att flytta ut exempel till doc...

## DSL

Det börjar bli väldigt många metoder i Query. Flytta det som inte är core till
domain specific language. Något i stil med `QueryDslTrait`..

## Macroable

Gör query macroable så att den enkelt kan lägga till sökfunktioner efter hand..

```php
Query::macro('whereIsPayment', function () {
    return $this->whereDescription('/^Utbetalning$/');
});
```

Detta kan sedan användas som

```php
$bok->select()->transactions()->whereIsPayment()->asArray();
```

Kanske kan jag stryka en del metoder från Query efter detta? Klassen börjar bli
lite väl tjock...

## Transaktioner

1. En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...
1. Här ingår även stöd för BTRANS och RTRANS till sie4

## Huvudbok

Består av summeringar för varje konto:

1. ingående balans (vid årets början, från "ingående balans")
1. ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
1. poster (Transactions) som berör kontot (varje transaktion listas och behöver veta villket verifikat det tillhör)
1. utgående saldo

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
