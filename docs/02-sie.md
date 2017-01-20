# Parsing and writing SIE files

Accounting data can be read and written in the [SIE](http://www.sie.se/) file format.

- [Parsing SIE files](#parsing-sie-files)
- [Writing SIE files](#writing-sie-files)

## Parsing SIE files

<!-- @expectOutput "/^1100.00$/" -->
```php
namespace byrokrat\accounting\Sie4\Parser;

/** @var \byrokrat\accounting\Sie4\Parser\Parser $parser */
$parser = (new ParserFactory)->createParser();

/** @var \byrokrat\accounting\Container $content */
$content = $parser->parse("
    #FLAGGA 1
    #KONTO 1920 Bank
    #KONTO 3000 Income
    #VER \"\" 1 20160830 \"description\"
    {
        #TRANS  1920 {} 100.00
        #TRANS  3000 {} -100.00
    }
");

// Outputs '1'
echo $content->getAttribute('FLAGGA');

/** @var \byrokrat\accounting\Verification[] $verifications */
$verifications = $content->query()->verifications()->toArray();

// Outputs '100.00'
echo $verifications[0]->getMagnitude();
```

## Writing SIE files

<!-- @expectOutput "/^\#FLAGGA 0/" -->
```php
namespace byrokrat\accounting;
use byrokrat\amount\Amount;

echo (new Sie4\Writer\Writer)->generate(
    (new Sie4\Writer\Settings)->setTargetCompany('my-company'),
    new Query([
        (new Verification)
            ->addTransaction(new Transaction(new Account\Asset('1920', 'Bank'), new Amount('100')))
            ->addTransaction(new Transaction(new Account\Earning('3000', 'Intänk'), new Amount('-100')))
    ])
);
```

&larr; [Querying accounting data](01-querying.md) | [Generating verifications using templates](03-templates.md) &rarr;
