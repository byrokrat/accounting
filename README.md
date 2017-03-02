# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)
[![Dependency Status](https://img.shields.io/gemnasium/byrokrat/accounting.svg?style=flat-square)](https://gemnasium.com/byrokrat/accounting)

> NOTE! This package is under development and the API subject to change.

Analysis and generation of bookkeeping data according to Swedish standards.

Installation
------------
```shell
composer require byrokrat/accounting:dev-master
```

Usage
-----
[Read the documentation here.](docs)

Although it would be possible to build a general bookkeeping application around
*Accounting* it was developed with two distinct scenarios in mind:

1. Analyzing accounting data (possibly exported from general bookkeeping).
1. Generating bookkeeping data using templates (and possibly import to general
   bookkeeping).

To enable import and export of bookkeeping data *Accounting* supports parsing
and generating files in the [SIE](docs/02-sie.md) file format.

### Analyzing data

<!-- @expectOutput "/^-300\.00Verification using account 1921$/" -->
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

$ledger = new Query([
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

$ledger->transactions()->each(function ($transaction) use (&$summaries) {
    $key = $transaction->getAccount()->getId();
    $summaries[$key] = $summaries[$key] ?? new TransactionSummary;
    $summaries[$key]->addToSummary($transaction);
});

// Outputs -300
echo $summaries[3000]->getOutgoingBalance();

// Select verifications concerning a specific account (outputs 'Verification using account 1921')
echo $ledger->verifications()->withAccount('1921')->first()->getDescription();
```

Documentation
-------------
- [Start](docs)
- [Querying accounting data](docs/01-querying.md)
- [Parsing and writing SIE files](docs/02-sie.md)
- [Generating verifications using templates](docs/03-templates.md)
