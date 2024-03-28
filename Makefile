.DEFAULT_GOAL = phpunit

.PHONY: phpunit
phpunit:
	php vendor/bin/phpunit tests/