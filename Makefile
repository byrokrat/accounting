COMPOSER_CMD=composer
PHIVE_CMD=phive

PHPUNIT_CMD=tools/phpunit
README_TESTER_CMD=tools/readme-tester
PHPSTAN_CMD=tools/phpstan
PHPCS_CMD=tools/phpcs
PHPEG_CMD=vendor/bin/phpeg

SIE4_GRAMMAR=src/Sie4/Parser/Grammar.php

.DEFAULT_GOAL=all

.PHONY: all
all: test analyze

$(SIE4_GRAMMAR): src/Sie4/Parser/Grammar.peg $(PHPEG_CMD)
	$(PHPEG_CMD) generate $<

.PHONY: clean
clean:
	rm $(SIE4_GRAMMAR) -f
	rm composer.lock -f
	rm -rf vendor
	rm -rf tools
	rm -f phive.xml

.PHONY: test
test: phpunit docs integrations

.PHONY: phpunit
phpunit: vendor/installed $(PHPUNIT_CMD) $(SIE4_GRAMMAR)
	$(PHPUNIT_CMD)

.PHONY: integrations
integrations: vendor/installed $(PHPUNIT_CMD) $(SIE4_GRAMMAR)
	$(PHPUNIT_CMD) integrations

.PHONY: docs
docs: vendor/installed $(README_TESTER_CMD) $(SIE4_GRAMMAR)
	$(README_TESTER_CMD) README.md

.PHONY: analyze
analyze: phpstan phpcs

.PHONY: phpstan
phpstan: vendor/installed $(PHPSTAN_CMD)
	$(PHPSTAN_CMD) analyze -c phpstan.neon -l 7 src

.PHONY: phpcs
phpcs: $(PHPCS_CMD)
	$(PHPCS_CMD)

composer.lock: composer.json
	@echo composer.lock is not up to date

vendor/installed: composer.lock
	$(COMPOSER_CMD) install
	touch $@

$(PHPUNIT_CMD):
	$(PHIVE_CMD) install phpunit:8 --trust-gpg-keys 4AA394086372C20A

$(README_TESTER_CMD):
	$(PHIVE_CMD) install hanneskod/readme-tester:1 --force-accept-unsigned

$(PHPSTAN_CMD):
	$(PHIVE_CMD) install phpstan

$(PHPCS_CMD):
	$(PHIVE_CMD) install phpcs

$(PHPEG_CMD): vendor/installed
