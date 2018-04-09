OS := $(shell uname)

# Docker management commands
start:
ifeq ($(OS),Darwin)
	@docker volume create --name=app-sync
	@docker-compose -f docker-compose-mac.yml up -d
	@docker-sync start
else
	@docker-compose up -d
endif

stop:
ifeq ($(OS),Darwin)
	@docker-compose stop
	@docker-sync stop
else
	@docker-compose stop
endif


# Application management commands
build:
	@docker-compose exec php bin/build
	@docker-compose exec php chown -R www-data:www-data var


# Clean application
clean:
	@docker-compose exec php bin/console cache:clear --env=prod --no-warmup --no-debug
	@docker-compose exec php bin/console cache:clear --env=dev --no-warmup
	@docker-compose exec php bin/console cache:clear --env=test --no-warmup


# Application testing commands
test:
	@docker-compose exec php vendor/bin/codecept run


# Log management commands
log-sev:
	@docker-compose logs -f nginx php

log-dev:
	@docker-compose exec php multitail -cS symfony var/logs/dev.log

log-prod:
	@docker-compose exec php multitail -cS symfony var/logs/prod.log


# Database management commands
db-con:
	@docker-compose exec mysql mysql -uapicore -papicore


# Open application commands
open-dev:
ifeq ($(OS),Darwin)
	@open http://localhost:8001/app_dev.php
else
	@xdg-open http://localhost:8001/app_dev.php
endif

open-prod:
ifeq ($(OS),Darwin)
	@open http://localhost:8001
else
	@xdg-open http://localhost:8001
endif

