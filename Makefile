OS := $(shell uname)

# Docker management commands
start:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d
	docker-sync start
	make build
else
	docker-compose up -d
	make build
endif

stop:
ifeq ($(OS),Darwin)
	docker-compose stop
	docker-sync stop
else
	docker-compose stop
endif


# Application management commands
build:
	bash bin/build


# Log management commands
log-sev:
	docker-compose logs -f nginx php

log-dev:
	docker-compose exec php multitail -cS symfony var/logs/dev.log

log-prod:
	docker-compose exec php multitail -cS symfony var/logs/prod.log


# Database management commands
db-con:
	docker-compose exec mysql mysql -uapicore -papicore apicore


# Open application commands
open-dev:
ifeq ($(OS),Darwin)
	open http://localhost:8001/app_dev.php
else
	xdg-open http://localhost:8001/app_dev.php
endif

open-prod:
ifeq ($(OS),Darwin)
	open http://localhost:8001
else
	xdg-open http://localhost:8001
endif

