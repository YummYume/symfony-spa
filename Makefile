COMPOSE=docker compose
COMPOSECI=$(COMPOSE) -f docker-compose.ci.yml
EXECPHP=$(COMPOSE) exec php
EXECENCORE=$(COMPOSE) exec encore
EXECREDIS=$(COMPOSE) exec redis
EXECNGINX=$(COMPOSE) exec nginx
EXECDB=$(COMPOSE) exec db
ifeq ($(OS), Windows_NT)
	ENVIRONMENT=Windows
else
	ENVIRONMENT=$(shell bash ./scripts/get-env.sh)
endif

# Starting/stopping the project
start: build-no-cache up-recreate composer db perm

build:
	$(COMPOSE) build --force-rm

build-no-cache:
	$(COMPOSE) build --no-cache --force-rm

up:
	$(COMPOSE) up -d --remove-orphans

up-recreate:
	$(COMPOSE) up -d --remove-orphans --force-recreate

stop:
	$(COMPOSE) stop

down:
	$(COMPOSE) down

# SSH
ssh:
	$(EXECPHP) sh

ssh-encore:
	$(EXECENCORE) sh

ssh-redis:
	$(EXECREDIS) sh

ssh-db:
	$(EXECDB) sh

# Installations
composer:
	$(EXECPHP) composer install

yarn:
	$(EXECENCORE) yarn install

# Perm
perm:
ifeq ($(ENVIRONMENT),Linux)
	sudo chown -R $(USER):$(USER) .
	sudo chown -R www-data:$(USER) ./public/
	sudo chmod -R g+rwx .
else
	$(EXECPHP) chown -R www-data:root public/
endif

# DB
db: db-drop db-create migration fixtures

db-create:
	$(EXECPHP) php bin/console d:d:c --if-not-exists

db-drop:
	$(EXECPHP) php bin/console d:d:d --if-exists --force

schema:
	$(EXECPHP) php bin/console d:s:u --force

migration:
	$(EXECPHP) php bin/console d:m:m -n --allow-no-migration --all-or-nothing

migration-diff:
	$(EXECPHP) php bin/console d:m:diff

fixtures:
	$(EXECPHP) php bin/console d:f:l -n

db-test:
	$(COMPOSECI) exec php php bin/console --env=test d:d:d --if-exists --force
	$(COMPOSECI) exec php php bin/console --env=test d:d:c --if-not-exists
	$(COMPOSECI) exec php php bin/console --env=test d:m:m -n --allow-no-migration --all-or-nothing
	$(COMPOSECI) exec php php bin/console --env=test d:f:l -n

# Services
rabbitmq-consume:
	$(EXECPHP) php bin/console messenger:consume -vv

# Containers
list-containers:
	docker compose ps -a

healthcheck-db:
	docker inspect --format "{{json .State.Health }}" db

healthcheck-php:
	docker inspect --format "{{json .State.Health }}" php

# Logs
logs:
	$(COMPOSE) logs

logs-php:
	$(COMPOSE) logs php

logs-encore:
	$(COMPOSE) logs encore

# Cache
cc:
	$(EXECPHP) bin/console c:cl --no-warmup
	$(EXECPHP) bin/console c:warmup

# Linting
lint: php-cs-fixer eslint prettier

php-cs-fixer:
	$(EXECPHP) sh -c "PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src -v --dry-run"

eslint:
	$(EXECENCORE) yarn lint

prettier:
	$(EXECENCORE) yarn prettier -c

# Fixing
fix: php-cs-fixer-fix eslint-fix prettier-fix

php-cs-fixer-fix:
	$(EXECPHP) sh -c "PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src"

eslint-fix:
	$(EXECENCORE) yarn lint --fix

prettier-fix:
	$(EXECENCORE) yarn prettier --write

# Testing
test:
	$(EXECPHP) php bin/phpunit

test-create:
	$(EXECPHP) php bin/console make:test

# CI
start-ci:
	$(COMPOSECI) rm -f
	$(COMPOSECI) build --no-cache --force-rm
	$(COMPOSECI) up -d
	$(COMPOSECI) exec php php bin/console --env=test assets:install
	$(COMPOSECI) exec php yarn dev
	make db-test
