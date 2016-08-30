# Byrokrat.Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)
[![Dependency Status](https://img.shields.io/gemnasium/byrokrat/accounting.svg?style=flat-square)](https://gemnasium.com/byrokrat/accounting)

> NOTE! This package is under development and the API subject to change.

Classes for working with bookkeeping data according to Swedish standards.

Installation
------------
```shell
composer require byrokrat/accounting
```

Documentation
-------------
- [Querying accounting data](docs/01-querying.md)
- [Parsing and writing SIE files](02-sie.md)
- [Generating verifications using templates](03-templates.md)

Usage
-----
### Building queries

The package is shipped with a generic solution for querying accounting data: the
[Query](/src/Query.php) object.

<!-- @expectOutput /^-300\.00Verification using account 1921$/ -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Currency\SEK;

// Create an account plan

$accountFactory = new AccountFactory;

$accounts = new Query([
    $accountFactory->createAccount('1920', 'Bank'),
    $accountFactory->createAccount('1921', 'Cash'),
    $accountFactory->createAccount('3000', 'Income'),
]);

// Create some verifications

$verifications = new Query([
    (new Verification)
        ->setDescription('Verification text')
        ->addTransaction(new Transaction($accounts->findAccount('1920'), new SEK('100')))
        ->addTransaction(new Transaction($accounts->findAccount('3000'), new SEK('-100')))
    ,
    (new Verification)
        ->setDescription('Verification using account 1921')
        ->addTransaction(new Transaction($accounts->findAccount('1921'), new SEK('200')))
        ->addTransaction(new Transaction($accounts->findAccount('3000'), new SEK('-200')))
]);

// Calculate account balances

$summaries = [];

$verifications->transactions()->each(function ($transaction) use (&$summaries) {
    $key = $transaction->getAccount()->getId();
    $summaries[$key] = $summaries[$key] ?? new TransactionSummary;
    $summaries[$key]->addToSummary($transaction);
});

// Outputs -300
echo $summaries[3000]->getOutgoingBalance();

// Iterate over verifications concerning a specific account

$verificationsUsingAccount1921 = $verifications->verifications()->where(function ($item) {
    return $item instanceof Account && $item->getId() == '1921';
})->toArray();

// Outputs 'Verification using account 1921'
echo $verificationsUsingAccount1921[0]->getDescription();
```
