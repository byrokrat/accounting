#!/usr/bin/env sh
set -e

phpunit --verbose
readme-tester test README.md docs -v
phpunit integrations --verbose
phpcs
