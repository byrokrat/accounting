# ledgr/accounting [![Build Status](https://travis-ci.org/ledgr/accounting.png)](https://travis-ci.org/ledgr/accounting) [![Dependency Status](https://gemnasium.com/ledgr/accounting.png)](https://gemnasium.com/ledgr/accounting) [![Code Coverage](https://scrutinizer-ci.com/g/ledgr/accounting/badges/coverage.png?s=a4b44f84ab03cc2d0e0ca5b76393faa86b5965ae)](https://scrutinizer-ci.com/g/ledgr/accounting/)


Classes for working with bookkeeping data. Specifically transaction
data can be read and written in the SIE format. Accounting templates from the
VISMA series of accounting software is also supported.


Installation using [composer](http://getcomposer.org/)
------------------------------------------------------
Simply add `ledgr/accounting` to your list of required libraries.


Run tests  using [phpunit](http://phpunit.de/)
----------------------------------------------
To run the tests you must first install dependencies using composer.

    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install
    $ phpunit
