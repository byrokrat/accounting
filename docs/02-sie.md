# Parsing and writing SIE files

Accounting data can be read and written in the [SIE](http://www.sie.se/) file format.

- [Parsing SIE files](#parsing-sie-files)
- [Writing SIE files](#writing-sie-files)

## Parsing SIE files

<!-- @expectOutput "/^1100.00$/" -->
```php
namespace byrokrat\accounting\Sie4\Parser;

/** @var \byrokrat\accounting\Sie4\Parser\Parser $parser */
$parser = (new Sie4ParserFactory)->createParser();

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
echo $content->getAttribute('flag');

/** @var \byrokrat\accounting\Verification\VerificationInterface[] $verifications */
$verifications = $content->select()->verifications()->asArray();

// Outputs '100.00'
echo $verifications[0]->getMagnitude();
```

## Writing SIE files

<!-- @expectOutput "/^\#FLAGGA 0/" -->
```php
namespace byrokrat\accounting;

use byrokrat\amount\Amount;

echo (new Sie4\Writer\Sie4Writer)->generateSie(
    new Container(
        new Verification\Verification(
            2,
            new \DateTimeImmutable,
            new \DateTimeImmutable,
            '',
            '',
            new Transaction\Transaction(
                0,
                new \DateTimeImmutable,
                '',
                '',
                new Amount('100'),
                new Amount('0'),
                new Dimension\AssetAccount('1920', 'Bank')
            ),
            new Transaction\Transaction(
                0,
                new \DateTimeImmutable,
                '',
                '',
                new Amount('-100'),
                new Amount('0'),
                new Dimension\AssetAccount('3000', 'Intänk')
            )
        ),
        new Verification\Verification(
            1,
            new \DateTimeImmutable,
            new \DateTimeImmutable,
            '',
            '',
            new Transaction\Transaction(
                0,
                new \DateTimeImmutable,
                '',
                '',
                new Amount('100'),
                new Amount('0'),
                new Dimension\AssetAccount('1920', 'Bank')
            ),
            new Transaction\Transaction(
                0,
                new \DateTimeImmutable,
                '',
                '',
                new Amount('-100'),
                new Amount('0'),
                new Dimension\AssetAccount('3000', 'Intänk')
            )
        )
    )
);
```

&larr; [Querying accounting data](01-querying.md) | [Generating verifications using templates](03-templates.md) &rarr;
