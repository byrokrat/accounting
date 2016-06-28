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
> NOTE! This package is under development and the API subject to change.

### Building queries

The package is shipped with a generic solution for querying accounting data: the
[Query](/src/Query.php) object.

<!-- @expectOutput /^-300\.00Verification using account 1921$/ -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Currency\SEK;

// Build accounts
$accountFactory = new AccountFactory;

$accounts = new AccountSet(...[
    $accountFactory->createAccount(1920, 'Bank'),
    $accountFactory->createAccount(1921, 'Cash'),
    $accountFactory->createAccount(3000, 'Income'),
]);

// Create a set of verifications
$verifications = new Query([
    new Verification(
        'Verification text',
        new \DateTimeImmutable,
        new Transaction($accounts->getAccountFromNumber(1920), new SEK('100')),
        new Transaction($accounts->getAccountFromNumber(3000), new SEK('-100'))
    ),
    new Verification(
        'Verification using account 1921',
        new \DateTimeImmutable,
        new Transaction($accounts->getAccountFromNumber(1921), new SEK('200')),
        new Transaction($accounts->getAccountFromNumber(3000), new SEK('-200'))
    )
]);


// Calculate account balances

$summaries = [];

$verifications->transactions()->each(function ($transaction) use (&$summaries) {
    $key = $transaction->getAccount()->getNumber();
    $summaries[$key] = $summaries[$key] ?? new Summary;
    $summaries[$key]->addTransaction($transaction);
});

// Outputs -300
echo $summaries[3000]->getOutgoingBalance();


// Iterate over verifications concerning a specific account

$verificationsUsingAccount1921 = $verifications->verifications()->where(function ($item) {
    return $item instanceof Account && $item->getNumber() == 1921;
})->toArray();

// Outputs 'Verification using account 1921'
echo $verificationsUsingAccount1921[0]->getText();
```

### Generating sie

Transaction data can be read and written in the [SIE](http://www.sie.se/) format.

<!-- @expectOutput /^\#FLAGGA 0/ -->
```php
namespace byrokrat\accounting;
use byrokrat\amount\Amount;

echo (new Sie\Writer)->generate(
    (new Sie\Settings)->setTargetCompany('my-company'),
    new Query([
        (new Verification('Ver A'))->addTransaction(
            new Transaction(new Account\Asset(1920, 'Bank'), new Amount('100')),
            new Transaction(new Account\Earning(3000, 'Intänk'), new Amount('-100'))
        )
    ])
);
```

Todo
----
See TODO comments spread out in source.

Credits
-------
@author Hannes Forsgård (hannes.forsgard@fripost.org)
