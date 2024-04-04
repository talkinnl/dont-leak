.DEFAULT_GOAL = all

.PHONY: phpunit
phpunit:
	php vendor/bin/phpunit tests/

.PHONY: phpunit-10
phpunit-10:
	@echo ""
	@echo "==== PHPUNIT 10 ===="
	composer require --with-all-dependencies phpunit/phpunit ^10.5
	php vendor/bin/phpunit tests/

.PHONY: phpunit-11
phpunit-11:
	@echo ""
	@echo "==== PHPUNIT 11 ===="
	composer require --with-all-dependencies phpunit/phpunit ^11.0
	php vendor/bin/phpunit tests/

.PHONY: phpunit-all
phpunit-all: phpunit-10 phpunit-11
	git checkout composer.json

.PHONY: all
all: phpunit-all
