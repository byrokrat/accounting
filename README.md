# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![license](https://img.shields.io/github/license/byrokrat/accounting.svg?maxAge=2592000&style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.com/github/byrokrat/accounting)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/accounting.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/accounting)

Analysis and generation of bookkeeping data according to Swedish standards.

## Installation

```shell
composer require byrokrat/accounting
```

## Why?

Although it would be possible to build a general bookkeeping application on top
of Accounting this was never the primary concern. The motivation for creating
Accounting was to provide solutions for two scenarios:

1. The need to generate bookkeeping data using templates (and possibly import to
   general bookkeeping).
1. The need to analyze accounting data (possibly exported from general
   bookkeeping).

To enable import and export of bookkeeping data Accounting supports parsing
and generating files in the [SIE](http://www.sie.se/) file format.

## Usage

1. [Generating accounting data using templates](#generating-accounting-data-using-templates)
1. [Writing SIE files](#writing-sie-files)
1. [Parsing SIE files](#parsing-sie-files)
1. [Writing macros](#writing-macros)
1. [Listing accounts](#listing-accounts)
1. [Sorting transactions into a ledger (huvudbok)](#sorting-transactions-into-a-ledger-huvudbok)

### Generating accounting data using templates

First we create an accounting template. Values enclosed in curly braces `{}`
are placeholders for values supplied at render time.

<!-- @example template -->
```php
use byrokrat\accounting\Template\VerificationTemplate;

$template = new VerificationTemplate([
    'description' => '{description}',
    'transactionDate' => '{now}',
    'transactions' => [
        [
            'account' => '1920',
            'amount' => '{bank_amount}'
        ],
        [
            'account' => '{account}',
            'amount' => '{income_amount}'
        ]
    ]
]);
```

Create an account plan, a set of accounts.

<!--
    @example accounts
    @include template
-->
```php
use byrokrat\accounting\Dimension\AccountFactory;

$accounts = (new AccountFactory)->createAccounts([
    '1920' => 'Bank',
    '3000' => 'Incomes',
    '3010' => 'Sales',
]);
```

And to render verifications we supply a list of translation values and the
account plan.

<!--
    @example verifications
    @include accounts
-->
```php
use byrokrat\accounting\Template\TemplateRenderer;
use byrokrat\accounting\Template\Translator;
use byrokrat\accounting\Container;

$renderer = new TemplateRenderer($accounts);

$verifications = new Container(
    $renderer->render(
        $template,
        new Translator([
            'description' => 'basic income',
            'bank_amount' => '999',
            'income_amount' => '-999',
            'account' => '3000'
        ])
    ),
    $renderer->render(
        $template,
        new Translator([
            'description' => 'sales',
            'bank_amount' => '333',
            'income_amount' => '-333',
            'account' => '3010'
        ])
    )
);
```

### Writing SIE files

<!--
    @example sie
    @include verifications
-->
```php
use byrokrat\accounting\Sie4\Writer\Sie4Writer;

$sie = (new Sie4Writer)->generateSie($verifications);
```

### Parsing SIE files

<!--
    @example parsing-sie
    @include sie
    @expectOutput "/^999.00$/"
-->
```php
use byrokrat\accounting\Sie4\Parser\Sie4ParserFactory;

$parser = (new Sie4ParserFactory)->createParser();

$container = $parser->parse($sie);

// Outputs '999.00'
echo $container->select()->verifications()->getFirst()->getMagnitude();
```

### Writing macros

Macros expose the posibility to extend the query api on the fly, without having
to subclass the Query class itself. It is suitable for adding project specific
order and filter methods. If we for example whant to order account objects
based on number we can define a macro for this:

<!--
    @example macro
-->
```php
byrokrat\accounting\Query::macro('orderById', function () {
    return $this->orderBy(function ($left, $right) {
        return $left->getId() <=> $right->getId();
    });
});
```

### Listing accounts

We can use our newly defined macro to order all account objects in a container.

<!--
    @example list-accounts
    @include verifications
    @include macro
-->
```php
$orderedAccounts = $verifications->select()->uniqueAccounts()->orderById()->asArray();
```

### Sorting transactions into a ledger (huvudbok)

An example of how Accounting may be used to sort transactions inte a ledger
(or *huvudbok* as it is known in swedish).

<!--
    @example ledger
    @include verifications
    @include macro
    @expectOutput "/Outgoing balance 1332.00/"
-->
```php
use byrokrat\accounting\Processor\TransactionProcessor;

(new TransactionProcessor)->processContainer($verifications);

$verifications->select()->uniqueAccounts()->orderById()->each(function ($account) {
    echo "{$account->getId()}: {$account->getDescription()}\n";
    echo "Incoming balance {$account->getAttribute('summary')->getIncomingBalance()}\n\n";

    $currentBalance = $account->getAttribute('summary')->getIncomingBalance();

    foreach ($account->getAttribute('transactions') as $trans) {
        echo $trans->getVerificationId(),
            "\t",
            $trans->getDescription(),
            "\t",
            $trans->getAmount(),
            "\t",
            $currentBalance = $currentBalance->add($trans->getAmount()),
            "\n";
    }

    echo "\nOutgoing balance {$account->getAttribute('summary')->getOutgoingBalance()}\n\n";
    echo "----------\n\n";
});
```

## Hacking

With [composer](https://getcomposer.org/) installed as `composer`

```shell
make
```

Or use something like

```shell
make COMPOSER_CMD=./composer.phar
```
