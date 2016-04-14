# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)
[![Dependency Status](https://img.shields.io/gemnasium/byrokrat/accounting.svg?style=flat-square)](https://gemnasium.com/byrokrat/accounting)

Classes for working with bookkeeping data according to Swedish standards.

Installation
------------
```shell
composer require byrokrat/accounting
```

Usage
-----
Transaction data can be read and written in the SIE format.

### Generate SIE data
<!-- @expectOutput /^\#FLAGGA 0/ -->
```php
namespace byrokrat\accounting;
use byrokrat\amount\Amount;

echo (new Sie\Writer)->generate(
    (new Sie\Settings)->setTargetCompany('my-company'),
    new VerificationSet(
        (new Verification('Ver A'))->addTransaction(
            new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100')),
            new Transaction(new Account\Earning(3000, 'Intänk'), new Amount('-100'))
        )
    )
);
```

Credits
-------
Accounting is released under the [GNU General Public License](LICENSE)

@author Hannes Forsgård (hannes.forsgard@fripost.org)
