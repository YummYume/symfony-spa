PWD=$(shell pwd)
COMPOSE=docker compose
COMPOSECI=$(COMPOSE) -f docker-compose.ci.yml
COMPOSEPROD=$(COMPOSE) -f docker-compose.prod.yml --env-file .env.prod
EXECPHP=$(COMPOSE) exec php
EXECENCORE=$(COMPOSE) exec encore
EXECREDIS=$(COMPOSE) exec redis
EXECNGINX=$(COMPOSE) exec nginx
EXECDB=$(COMPOSE) exec db
DAHL=docker run --rm -it -v $(PWD):/app -w /app spacelocust/dahl:latest

# Functions
capitalize = $(shell echo $(1) | sed 's/\([a-z]\)\([a-zA-Z0-9]*\)/\u\1\2/g')
lowercase = $(shell echo $(1) | tr '[:upper:]' '[:lower:]')
path_reformator = $(subst /,\\,$(1))

ifeq ($(OS), Windows_NT)
	ENVIRONMENT=Windows
else
	ENVIRONMENT=$(shell bash ./scripts/get-env.sh)
endif

# Starting/stopping the project
start: build-no-cache up-recreate composer yarn db perm

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
db: db-drop db-create meilisearch-delete migration meilisearch-create fixtures

db-create:
	$(EXECPHP) php bin/console d:d:c --if-not-exists

db-drop:
	$(EXECPHP) php bin/console d:d:d --if-exists --force

schema:
	$(EXECPHP) php bin/console d:s:u --force

migration:
	$(EXECPHP) php bin/console d:m:m -n --allow-no-migration --all-or-nothing

migration-diff:
	$(EXECPHP) php bin/console make:migration

fixtures:
	$(EXECPHP) php bin/console d:f:l -n

db-test:
	$(COMPOSECI) exec php php bin/console --env=test d:d:d --if-exists --force
	$(COMPOSECI) exec php php bin/console --env=test d:d:c --if-not-exists
	$(COMPOSECI) exec php php bin/console --env=test meili:delete
	$(COMPOSECI) exec php php bin/console --env=test d:m:m -n --allow-no-migration --all-or-nothing
	$(COMPOSECI) exec php php bin/console --env=test meili:create
	$(COMPOSECI) exec php php bin/console --env=test d:f:l -n

# Services
# Rabbitmq
rabbitmq-consume:
	$(EXECPHP) php bin/console messenger:consume -vv

# Meilisearch
meilisearch-create:
	$(EXECPHP) php bin/console meili:create

meilisearch-import:
	$(EXECPHP) php bin/console meili:import

meilisearch-update:
	$(EXECPHP) php bin/console meili:import --update-settings

meilisearch-clear:
	$(EXECPHP) php bin/console meili:clear

meilisearch-delete:
	$(EXECPHP) php bin/console meili:delete

# Debug
dump:
	$(EXECPHP) php bin/console server:dump

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
	make liip-clear

liip-clear:
	$(EXECPHP) bin/console liip:imagine:cache:remove

# Linting
lint: php-cs-fixer eslint

php-cs-fixer:
	$(EXECPHP) sh -c "PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix src -v --dry-run"

eslint:
	$(EXECENCORE) yarn lint

prettier:
	$(EXECENCORE) yarn prettier -c

# Fixing
fix: php-cs-fixer-fix eslint-fix

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

# Dahl
dahl:
	$(DAHL) $(c)

dahl-icon:
	$(DAHL) run i-twig -n $(call lowercase, $(n))

dahl-translation:
	$(DAHL) run trans -n $(call lowercase, $(n)) -s +intl-icu.fr
	$(DAHL) run trans -n $(call lowercase, $(n)) -s +intl-icu.en

# n for name and l for location (sub directory)
dahl-component:
	$(DAHL) run c-class \
		--to="./src/Components$(if $(l),/$(call capitalize, $(l)),)" \
		-n="$(call capitalize, $(n))" \
		--props '{"namespace":"$(if $(l),App\\Components\\$(call path_reformator,$(call capitalize,$(l))),App\\Components)","twigLocation":"components/$(if $(l),$(l)/$(n).html.twig,$(n).html.twig)","twigName":"$(n)"}' \

	$(DAHL) run c-twig \
		--to="./templates/components/$(if $(l),$(l),"")" \
		-n="$(call lowercase, $(n))" \
		--props '{"twigLocation":"$(l)","twigName":"$(n)"}'

# n for name
dahl-admin:
	$(DAHL) run a-controller \
		-n="$(call capitalize, $(n))" \
		--props '{"name":"$(n)"}' \

	$(DAHL) run a-template \
		--to="./templates/admin/$(n)" \
		--props '{"name":"$(n)"}' \

	make perm

dahl-admin-form:
	$(DAHL) run a-form-edit \
		--to="./templates/admin/$(n)" \
		--props '{"name":"$(n)"}' \

	$(DAHL) run a-form-content \
		--to="./templates/admin/$(n)" \
		--props '{"name":"$(n)"}' \

	$(DAHL) run a-form-delete \
		--to="./templates/admin/$(n)" \
		--props '{"name":"$(n)"}' \

	$(DAHL) run a-form \
		--to="./templates/admin/$(n)" \
		--props '{"name":"$(n)"}' \

	make perm

# Deploy
deploy:
	git fetch origin master
	git reset --hard origin/master
	$(COMPOSEPROD) build --no-cache --force-rm
	$(COMPOSEPROD) up -d --remove-orphans --force-recreate
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console d:m:m -n --allow-no-migration --all-or-nothing
	$(COMPOSEPROD) exec php yarn build
	$(COMPOSEPROD) exec php yarn cache clean
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console cache:clear
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console cache:warmup
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php composer dump-env prod
	$(COMPOSEPROD) exec php chown -R www-data:www-data ./var/ ./public/media/
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console meili:create
	$(COMPOSEPROD) exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console meili:import
