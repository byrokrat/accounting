COMPOSER_CMD=composer
PHIVE_CMD=phive

PHPUNIT_CMD=vendor/bin/phpunit
README_TESTER_CMD=tools/readme-tester
PHPSTAN_CMD=tools/phpstan
PHPCS_CMD=tools/phpcs
PHPEG_CMD=tools/phpeg

SIE4_GRAMMAR=src/Sie4/Parser/Grammar.php

.DEFAULT_GOAL=all

.PHONY: all clean

all: test analyze

clean:
	rm $(SIE4_GRAMMAR) -f
	rm composer.lock -f
	rm -rf vendor
	rm -rf tools

$(SIE4_GRAMMAR): src/Sie4/Parser/Grammar.peg $(PHPEG_CMD)
	$(PHPEG_CMD) generate $<

#
# Test and analyze
#

.PHONY: test phpunit integrations docs

test: phpunit docs integrations

phpunit: vendor/installed $(SIE4_GRAMMAR) $(PHPUNIT_CMD)
	$(PHPUNIT_CMD)

integrations: vendor/installed $(SIE4_GRAMMAR) $(PHPUNIT_CMD)
	$(PHPUNIT_CMD) integrations

docs: vendor/installed $(SIE4_GRAMMAR) $(README_TESTER_CMD)
	$(README_TESTER_CMD) README.md

.PHONY: analyze phpstan phpcs

analyze: phpstan phpcs

phpstan: vendor/installed $(PHPSTAN_CMD)
	$(PHPSTAN_CMD) analyze -c phpstan.neon -l 8 src

phpcs: $(PHPCS_CMD)
	$(PHPCS_CMD)

#
# Dependencies
#

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor/installed: composer.lock
	$(COMPOSER_CMD) install
	touch $@

tools/installed:
	$(PHIVE_CMD) install --force-accept-unsigned --trust-gpg-keys 4AA394086372C20A,CF1A108D0E7AE720,0FD3A3029E470F86
	touch $@

$(PHPUNIT_CMD): vendor/installed
$(README_TESTER_CMD): tools/installed
$(PHPSTAN_CMD): tools/installed
$(PHPCS_CMD): tools/installed
$(PHPEG_CMD): tools/installed
