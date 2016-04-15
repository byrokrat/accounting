# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
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
> NOTE! This package is under development and the API may change.

Transaction data can be read and written in the [SIE](http://www.sie.se/) format.

### Generating sie
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

### Calculate account balances
<!-- @expectOutput /^300\.00$/ -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Amount;

// Create or accounts
$accounts = new AccountSet(
    new Account\Asset(1920, 'Bank'),
    new Account\Earning(3000, 'Income')
);

// Fetch verifications from persistent storage...
$verifications = new VerificationSet(
    (new Verification('First ver'))->addTransaction(
        new Transaction($accounts->getAccountFromNumber(1920), new Amount('100')),
        new Transaction($accounts->getAccountFromNumber(3000), new Amount('-100'))
    ),
    (new Verification('Second ver'))->addTransaction(
        new Transaction($accounts->getAccountFromNumber(1920), new Amount('200')),
        new Transaction($accounts->getAccountFromNumber(3000), new Amount('-200'))
    )
);

$summaries = (new AccountSummaryBuilder)
    ->setVerifications($verifications)
    ->processAccounts($accounts);

// Outputs 300
echo $summaries->getAccountFromNumber(1920)->getOutgoingBalance();
```

TODO
----
See TODO comments spread out in source.

Credits
-------
@author Hannes Forsgård (hannes.forsgard@fripost.org)
