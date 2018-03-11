OS := $(shell uname)

start-dev:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d
	docker-sync start
else
	docker-compose up -d
endif

stop-dev:
ifeq ($(OS),Darwin)
	docker-compose stop
	docker-sync stop
else
	docker-compose stop
endif

build:
	bash bin/build

open-api:
ifeq ($(OS),Darwin)
	open http://localhost:8001/app_dev.php
else
	xdg-open http://localhost:8001/app_dev.php
endif


