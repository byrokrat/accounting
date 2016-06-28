## Exceptions

* Fundera igenom vilken struktur jag vill ha på undantagen...

## Transaktioner

* En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...

## Huvudbok

Består av summeringar för varje konto:

* ingående balans (vid årets början, från "ingående balans")
* ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
* poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning)
* utgående saldo
* varje transaktion listas och behöver veta villket verifikat det tillhör..

## Verifikationslista

Verifikationer behöver kunna hålla koll på sina egna numreringar...

```php
(new Query($data))->verifications()->each(function (Verification $ver) {
    echo $ver->getVerificationNumber(); // eller motsvarande
    echo '...';
});
```

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
