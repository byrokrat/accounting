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

$accountFactory = new byrokrat\accounting\Dimension\AccountFactory;

$bank = $accountFactory->createAccount('1920', 'Bank');
$bank->setAttribute('incoming_balance', new byrokrat\amount\Amount('0'));

$incomes = $accountFactory->createAccount('3000', 'Incomes');
$incomes->setAttribute('incoming_balance', new byrokrat\amount\Amount('0'));

$accounts = new byrokrat\accounting\Container($bank, $incomes);

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
    @include container
    @include macro
-->
```php
$container->select()->accounts()->whereUnique()->orderById()->asArray();
```

## Sorting transactions into a ledger (huvudbok)

An example of how Accounting may be used to sort transactions inte a ledger
(or *huvudbok* as it is known in swedish).

<!--
    @example huvudbok
    @include container
    @include macro
    @expectOutput "/Outgoing balance 1000.00/"
-->
```php
(new byrokrat\accounting\Processor\TransactionProcessor)->processContainer($container);

$container->select()->accounts()->whereUnique()->orderById()->each(function ($account) {
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

[Parsing and writing SIE files](02-sie.md) &rarr;
