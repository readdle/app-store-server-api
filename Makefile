.PHONY: shell

COMPOSER_VER = 2.9.5
PHP_VER = 8.2.32

cs-check:
	docker run -t --rm -w /app -v .:/app php:${PHP_VER}-cli php vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

composer:
	docker run --rm -it -v $(shell pwd):/app -w /app composer:${COMPOSER_VER} bash

lint-74:
	docker run --rm -it -v $(shell pwd):/app -w /app php:7.4-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-80:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.0-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-81:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.1-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-82:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.2-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-83:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.3-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-84:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.4-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

lint-85:
	docker run --rm -it -v $(shell pwd):/app -w /app php:8.5-cli bash -c 'OUT=$$(find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 -P 4 php -l | grep -v "No syntax errors detected in "); echo "$$OUT"; [ -z "$$OUT" ]'

shell:
	docker run --rm -it -w /app/examples -v $(shell pwd):/app php:8.5.2-cli bash

stan:
	docker run -t --rm -w /app -v .:/app php:${PHP_VER}-cli php -d memory_limit=256M vendor/bin/phpstan

test:
	docker run -t --rm -w /app -v .:/app php:${PHP_VER}-cli php vendor/bin/phpunit
