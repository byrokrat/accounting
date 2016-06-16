# Byrokrat.Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting/?branch=master)
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
    new Journal(
        (new Verification('Ver A'))->addTransaction(
            new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100')),
            new Transaction(new Account\Earning(3000, 'Intänk'), new Amount('-100'))
        )
    )
);
```

### Calculate account balances
<!-- @expectOutput /^400\.00$/ -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Currency\SEK;

// Build accounts (specifying incoming balance for 1920)
$accounts = (new AccountSetBuilder)
    ->addAccount(1920, 'Bank', new SEK('100'))
    ->addAccount(3000, 'Income')
    ->getAccounts();

// Build jounrnal (fetching from persistent storage?)
$journal = (new JournalBuilder($accounts))
    ->addVerification(
        'First ver',
        new \DateTimeImmutable,
        [1920, new SEK('100')],
        [3000, new SEK('-100')]
    )
    ->addVerification(
        'Second ver',
        new \DateTimeImmutable,
        [1920, new SEK('200')],
        [3000, new SEK('-200')]
    )
    ->getJournal();

$summaries = (new AccountSummaryBuilder)
    ->setJournal($journal)
    ->setDefaultIncomingBalance(new SEK('0'))
    ->processAccounts($accounts);

// Outputs 400
echo $summaries->getAccountFromNumber(1920)->getOutgoingBalance();
```

Todo
----
See TODO comments spread out in source.

Credits
-------
@author Hannes Forsgård (hannes.forsgard@fripost.org)
