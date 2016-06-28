## Brygga till query..

* `getText()` i Verification, `getName()` i Account. Annar i andra? Standardisera namn... (se även Template)

* Endast använda mig av RuntimeException istället för alla konstiga olika jag har nu??
  Kolla igenom var de används någonstans...

## Transaktioner

* En transaction ska kunna vara struken, och ska i så fall inte räknas i arithmetiken...

## Huvudbok

Består av summeringar för varje konto:

* ingående balans (vid årets början, från "ingående balans")
* ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
* poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning)
* utgående saldo (beräknas)
* varje transaktion listas och behöver veta villket verifikat det tillhör..

## Verifikationslista

Verifikationer behöver kunna hålla koll på sina egna numreringar...

```php
(new Query($data))->verifications()->each(function (Verification $ver) {
    echo $ver->getVerificationNumber();
    echo '...';
});
```

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```
