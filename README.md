![byrokrat](res/logo.svg)

# Accounting

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/accounting.svg?style=flat-square)](https://packagist.org/packages/byrokrat/accounting)
[![Build Status](https://img.shields.io/travis/byrokrat/accounting/master.svg?style=flat-square)](https://travis-ci.com/github/byrokrat/accounting)

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
and generating files in the [SIE4](http://www.sie.se/) file format.

## Usage

1. [Generating accounting data using templates](#generating-accounting-data-using-templates)
1. [Handling monetary amounts](#handling-monetary-amounts)
1. [Writing SIE4 files](#writing-sie4-files)
1. [Parsing SIE4 files](#parsing-sie4-files)
1. [Querying accounting data](#querying-accounting-data)
1. [Writing macros](#writing-macros)

### Handling monetary amounts

*Accounting* uses [Moneyphp](https://moneyphp.org) to hande monetary amounts. More
information on the money api can be found on their website. In these examples
we need to format amounts, wich we do using the simple `DecimalMoneyFormatter`.

<!-- @example moneyFormatter -->
```php
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies\ISOCurrencies;

$moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());
```

### Generating accounting data using templates

First we create an accounting template. Values enclosed in curly braces `{}`
are placeholders for values supplied at render time.

<!-- @example template -->
```php
use byrokrat\accounting\Template\TransactionTemplate;
use byrokrat\accounting\Template\VerificationTemplate;

$template = new VerificationTemplate(
    description: '{description}',
    transactionDate: '{date}',
    transactions: [
        new TransactionTemplate(
            account: '1920',
            amount: '{bank_amount}'
        ),
        new TransactionTemplate(
            account: '{income_account}',
            amount: '{income_amount}'
        )
    ]
);
```

Create an account plan, a set of accounts.

<!--
    @example accounts
    @include template
-->
```php
use byrokrat\accounting\Container;
use byrokrat\accounting\Dimension\Account;

$accounts = new Container(
    new Account(id: '1920', description: 'Bank'),
    new Account(id: '3000', description: 'Incomes'),
    new Account(id: '3010', description: 'Sales'),
);
```

And to render verifications we supply a list of translation values and the
account plan.

<!--
    @example verifications
    @include accounts
-->
```php
use byrokrat\accounting\Template\TemplateRendererFactory;
use byrokrat\accounting\Template\Translator;

$renderer = (new TemplateRendererFactory)->createRenderer($accounts);

$verifications = new Container(
    $renderer->render(
        $template,
        new Translator([
            'description' => 'Some donation...',
            'date' => '2021-01-12',
            'bank_amount' => '999',
            'income_amount' => '-999',
            'income_account' => '3000'
        ])
    ),
    $renderer->render(
        $template,
        new Translator([
            'description' => 'Daily cash register',
            'date' => '2021-01-12',
            'bank_amount' => '333',
            'income_amount' => '-333',
            'income_account' => '3010'
        ])
    )
);
```

### Writing SIE4 files

<!--
    @example sie-generated
    @include verifications
-->
```php
use byrokrat\accounting\Sie4\Writer\Sie4iWriter;

$sie = (new Sie4iWriter)->generateSie($verifications);
```

### Parsing SIE4 files

<!--
    @example sie-parsed
    @include sie-generated
    @expectOutput "/Some donation.../"
-->
```php
use byrokrat\accounting\Sie4\Parser\Sie4ParserFactory;

$parser = (new Sie4ParserFactory)->createParser();

$container = $parser->parse($sie);

echo $container->select()->verifications()->first()->getDescription();
```

### Querying accounting data

#### Listing accounts

<!--
    @example list-accounts
    @include verifications
-->
```php
$orderedAccounts = $verifications->select()->accounts()->orderById()->asArray();
```

#### Calculate book magnitude

<!--
    @example calculate-magnitude
    @include verifications
    @include moneyFormatter
    @expectOutput "1332.00"
-->
```php
echo $moneyFormatter->format(
    $verifications->select()->verifications()->asSummary()->getMagnitude()
);
```

#### Sorting transactions into a ledger (huvudbok)

An example of how Accounting may be used to sort transactions inte a ledger
(or *huvudbok* as it is known in swedish).

<!--
    @example ledger
    @include verifications
    @include moneyFormatter
    @expectOutput "/Outgoing balance 1332.00/"
-->
```php
$verifications->select()->accounts()->orderById()->each(function ($account) use ($moneyFormatter) {
    printf(
        "%s %s\nIncoming balance %s\n",
        $account->getId(),
        $account->getDescription(),
        $moneyFormatter->format($account->getSummary()->getIncomingBalance())
    );

    foreach ($account->getTransactions() as $trans) {
        printf(
            "%s\t%s\t%s\n",
            $trans->getVerificationId(),
            $account->getDescription(),
            $moneyFormatter->format($trans->getAmount()),
        );
    }

    printf(
        "Outgoing balance %s\n\n",
        $moneyFormatter->format($account->getSummary()->getOutgoingBalance())
    );
});
```

### Writing macros

Macros expose the posibility to extend the query api on the fly, without having
to subclass the Query class itself. It is suitable for adding project specific
order and filter methods. If we for example whant to filter on description
we can define a macro for this:

<!--
    @example register-macro
-->
```php
use byrokrat\accounting\Query;

Query::macro('filterOnDescription', function (string $desc) {
    return $this->filter(
        fn($item) => str_contains($item->getDescription(), $desc)
    );
});
```

And then use it to query accounting data:

<!--
    @example filterOnDescription
    @include verifications
    @include register-macro
    @expectOutput "/Some donation.../"
-->
```php
echo $verifications->select()->filterOnDescription('donation')->first()->getDescription();
```

## Hacking

With [composer](https://getcomposer.org/) installed as `composer` and
[phive](https://phar.io/) installed as `phive`

```shell
make
```

Or use something like

```shell
make COMPOSER_CMD=./composer.phar PHIVE_CMD=./phive.phar
```
