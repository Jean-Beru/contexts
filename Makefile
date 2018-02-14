DC=docker-compose
RUN=$(DC) run --rm app

# install
install: vendor

# test
test: test-cs test-behat

test-cs:
	$(RUN) vendor/bin/php-cs-fixer fix src --dry-run

test-behat:
	$(RUN) dockerize -wait tcp://rabbitmq:5672 -timeout 10s
	$(RUN) vendor/bin/behat

# rules from files
vendor: composer.lock
	$(RUN) composer install -n --prefer-dist

composer.lock: composer.json
	@echo composer.lock is not up to date.
