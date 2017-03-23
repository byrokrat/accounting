# TODO

## Template

1. Template skapar verifikationer med vanlit amount-objekt. Ska vara SEK eller annan inställd valuta.
1. Där är också dålig kod med skumma array strukturer som behöver utvecklas...
1. Template skriver inte heller något datum till varken transaction eller verifikat.

## Kontoplaner

`AccountPlan` definierar metoder för gruppering vid rapportskrivning osv..

```php
class EUBAS97 implements AccountPlan {...}
```

## Cleanup repo

1. `bin` kan gå bort
1. `res` kan gå bort
1. `integrations` ska antagligen heta någonting annat...
