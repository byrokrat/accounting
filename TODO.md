## Kontoplaner

`AccountPlan` kan ärva `AccountSet` och lägga till metoder för gruppering vid
rapportskrivning osv..

```php
class EUBAS97 extends AccountSet implements AccountPlan {...}
```

## Huvudbok

Består av summeringar för varje konto:

* ingående balans (vid årets början, från "ingående balans")
* ingående saldo (vid periodens början, om huvudbok inte skrivs ut för hela året)
* poster (Transactions) som berör kontot (beräknas från verifikationer) (+ verifikationsbeskrivning)
* utgående saldo (beräknas)

## Verifikationslista
```php
(new Query($data))->verifications()->each(function (Verification $ver) {
    echo $ver->getVerificationNumber();
    echo '...';
});
```
