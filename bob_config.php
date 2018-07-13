<?php

namespace Bob\BuildConfig;

use byrokrat\accounting\Sie4\Parser\Sie4ParserFactory;

task('default', ['build_sie_parser', 'test', 'phpstan', 'sniff']);

desc('Run all tests');
task('test', ['phpunit', 'examples', 'integrations']);

desc('Run phpunit tests');
task('phpunit', function() {
    sh('phpunit', null, ['failOnError' => true]);
    println('Phpunit tests passed');
});

desc('Test examples');
task('examples', function() {
    sh('readme-tester README.md', null, ['failOnError' => true]);
    println('Examples passed');
});

desc('Integration tests');
task('integrations', function() {
    sh('phpunit integrations', null, ['failOnError' => true]);
    println('Integration tests passed');
});

desc('Run statical analysis using phpstan');
task('phpstan', function() {
    sh('phpstan analyze -c phpstan.neon -l 7 src', null, ['failOnError' => false]);
    println('Phpstan analysis passed');
});

desc('Run php code sniffer');
task('sniff', function() {
    sh('phpcs', null, ['failOnError' => false]);
    println('Syntax checker passed');
});

desc('Build sie parser');
task('build_sie_parser', ['src/Sie4/Parser/Grammar.php']);

fileTask('src/Sie4/Parser/Grammar.php', ['src/Sie4/Parser/Grammar.peg'], function() {
    println('Generating SIE4 parser');
    sh('phpeg generate src/Sie4/Parser/Grammar.peg', null, ['failOnError' => true]);
    println('[done]');
});

desc('Globally install development tools');
task('install_dev_tools', function() {
    sh('composer global require consolidation/cgr', null, ['failOnError' => true]);
    sh('cgr scato/phpeg:^1.0', null, ['failOnError' => true]);
    sh('cgr phpstan/phpstan', null, ['failOnError' => true]);
    sh('cgr phpunit/phpunit', null, ['failOnError' => true]);
    sh('cgr squizlabs/php_codesniffer', null, ['failOnError' => true]);
    sh('cgr hanneskod/readme-tester:^1.0@beta', null, ['failOnError' => true]);
});

desc('Validate the contents of a SIE4 file');
task('check_sie4_file', function() {
    println('Usage: bob check_sie4_file name=[filename]');
    require_once __DIR__ . '/vendor/autoload.php';
    $parser = (new Sie4ParserFactory)->createParser();
    $parser->parse(
        file_get_contents($_ENV['name'] ?? '')
    );
    print_r($parser->getErrorLog());
});
