# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)

> NOTE! This package is under development and the API subject to change.

Analysis and generation of bookkeeping data according to Swedish standards.

## Installation

```shell
composer require byrokrat/accounting:dev-master
```

## Usage

[Read the documentation here.](docs)

Although it would be possible to build a general bookkeeping application on top
of Accounting this was never the primary concern. The motivation for creating
Accounting was to provide solutions for two scenarios:

1. The need to generate bookkeeping data using templates (and possibly import to
   general bookkeeping).
1. The need to analyze accounting data (possibly exported from general
   bookkeeping).

To enable import and export of bookkeeping data Accounting supports parsing
and generating files in the [SIE](docs/02-sie.md) file format.

### Generating accounting data using templates

First we create an accounting template

<!-- @example template -->
```php
$template = new byrokrat\accounting\Template(
    'template_name',
    '{description}',
    ['1920', '{bank_amount}'],
    ['{account}', '{income_amount}']
);
```

To build verifications using our template we need an account plan

<!--
    @example accounts
    @include template
-->
```php
$accountFactory = new byrokrat\accounting\Dimension\AccountFactory;

$accounts = new byrokrat\accounting\Container(
    $accountFactory->createAccount('1920', 'Bank'),
    $accountFactory->createAccount('3000', 'Incomes'),
    $accountFactory->createAccount('3010', 'Sales')
);
```

And to create a verification we supply a list of translation values and the
account plan.

<!--
    @example verifications
    @include accounts
-->
```php
$verA = $template->build(
    [
        'description' => 'basic income',
        'bank_amount' => '999',
        'income_amount' => '-999',
        'account' => '3000'
    ],
    $accounts
);

$verB = $template->build(
    [
        'description' => 'sales',
        'bank_amount' => '333',
        'income_amount' => '-333',
        'account' => '3010'
    ],
    $accounts
);
```

### Querying data

We then query our generated accounting data using something like

<!--
    @example query
    @include verifications
    @expectOutput "sales"
-->
```php
$data = new byrokrat\accounting\Container($verA, $verB);

// Grab the first verification concerning account 3010 and output it's description
echo $data->select()->verifications()->whereAccount('3010')->getFirst()->getDescription();
```

For more examples see the documentation.

## Documentation

- [Start](docs)
- [Querying accounting data](docs/01-querying.md)
- [Parsing and writing SIE files](docs/02-sie.md)
- [Generating verifications using templates](docs/03-templates.md)

## Hacking

Install dependencies

```shell
composer install
```

Install development tools

```shell
composer global require chh/bob:^1.0@alpha
export PATH=$PATH:$HOME/.composer/vendor/bin/
bob install_dev_tools
```

Build and run tests

```shell
bob
```

Check the contents of an sie4 file

```shell
bob check_sie4_file name=[filename]
```
