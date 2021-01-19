<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

if (isset($argv[1])) {
    $source = $argv[1];
    $content = file_get_contents($argv[1]);
} else {
    echo "No input specified\n";
    echo "Usage: php check_sie4_file [filename]\n";
    exit(1);
}

echo "Parsing from: $source\n";

$parser = new byrokrat\accounting\Sie4\Parser\Sie4Parser();

$parser->parse($content);

echo "valid\n";
exit(0);
