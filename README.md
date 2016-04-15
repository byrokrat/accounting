# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?maxAge=2592000&style=flat-square)](LICENSE)
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

### Calculate account balance
<!-- @expectOutput /^400\.00$/ -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Currency\SEK;

$bank = new Account\Asset(1920, 'Bank');
$income = new Account\Earning(3000, 'Income');

// Fetch verifications from persistent storage...
$verifications = new VerificationSet(
    (new Verification('First ver'))->addTransaction(
        new Transaction($bank, new SEK('100')),
        new Transaction($income, new SEK('-100'))
    ),
    (new Verification('Second ver'))->addTransaction(
        new Transaction($bank, new SEK('200')),
        new Transaction($income, new SEK('-200'))
    )
);

// Setup incoming balance for account 1920
$balance = new AccountBalance($bank, new SEK('100'));

// Calculate outgoing balance
$processor = new TransactionProcessor;

$processor->onAccount($bank, function (Transaction $transaction) use ($balance) {
    $balance->addTransaction($transaction);
});

$processor->process($verifications);

// Outputs 400
echo $balance->getOutgoingBalance();
```

Credits
-------
Accounting is released under the [GNU General Public License](LICENSE).

@author Hannes Forsgård (hannes.forsgard@fripost.org)
