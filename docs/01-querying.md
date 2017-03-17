# Querying accounting data

The package is shipped with a generic solution for querying accounting data.

- [Writing macros](#writing-macros)
- [Listing accounts](#listing-accounts)
- [Sorting transactions into a ledger (huvudbok)](#sorting-transactions-into-a-ledger-huvudbok)

<!--
@example container

```php
$template = new byrokrat\accounting\Template(
    'template_name',
    'desc',
    ['1920', '{bank_amount}'],
    ['3000', '{income_amount}']
);

$accountFactory = new byrokrat\accounting\AccountFactory;

$accounts = new byrokrat\accounting\Container(
    $accountFactory->createAccount('1920', 'Bank')->setAttribute('incoming_balance', new byrokrat\amount\Amount('0')),
    $accountFactory->createAccount('3000', 'Incomes')->setAttribute('incoming_balance', new byrokrat\amount\Amount('0'))
);

$container = new byrokrat\accounting\Container(
    $template->build(
        [
            'bank_amount' => '999',
            'income_amount' => '-999'
        ],
        $accounts
    ),
    $template->build(
        [
            'bank_amount' => '1',
            'income_amount' => '-1'
        ],
        $accounts
    )
);
```
-->

## Writing macros

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

## Listing accounts

We can then use our newly defined macro to order all account objects in
a container.

<!--
    @example listAccounts
    @extends container
-->
```php
$container->select()->accounts()->whereUnique()->orderById()->asArray();
```

## Sorting transactions into a ledger (huvudbok)

An example of how Accounting may be used to sort transactions inte a ledger
(or *huvudbok* as it is known in swedish).

<!--
    @example huvudbok
    @extends container
    @expectOutput "/Outgoing balance 1000.00/"
-->
```php
$summaries = [];

$container->select()->transactions()->each(function ($trans) use (&$summaries) {
    $account = $trans->getAccount();

    $summaries[$account->getId()] = $summaries[$account->getId()] ?? [
        $account,
        new byrokrat\accounting\Summary($account->getAttribute('incoming_balance'))
    ];

    $summaries[$account->getId()][1]->addTransaction($trans);
});

ksort($summaries);

foreach ($summaries as list($account, $summary)) {
    echo "$account\n";
    echo "Incoming balance {$summary->getIncomingBalance()}\n\n";

    $currentBalance = $summary->getIncomingBalance();

    foreach ($summary->getTransactions() as $trans) {
        echo $trans->getAttribute('ver_num'),
            ' ',
            $trans->getDescription(),
            '" ',
            $trans->getAmount(),
            ' ',
            $currentBalance = $currentBalance->add($trans->getAmount()),
            "\n";
    }

    echo "\nOutgoing balance {$summary->getOutgoingBalance()}\n\n";
    echo "----------\n\n";
}
```

[Parsing and writing SIE files](02-sie.md) &rarr;
