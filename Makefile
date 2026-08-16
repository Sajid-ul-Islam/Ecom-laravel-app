# Convenience targets for the Deen Commerce docker compose stack.
# Everything delegates to ./dc.sh so there is a single source of truth.
# Usage: make up, make logs, make artisan ARGS="route:list", ...

.DEFAULT_GOAL := help

.PHONY: help up down restart destroy build logs ps health shell artisan composer npm migrate test sync

help:
	@./dc.sh help

up:
	@./dc.sh up

down:
	@./dc.sh down

restart:
	@./dc.sh restart

destroy:
	@./dc.sh destroy

build:
	@./dc.sh build

logs:
	@./dc.sh logs $(SVC)

ps:
	@./dc.sh ps

health:
	@./dc.sh health

shell:
	@./dc.sh shell

artisan:
	@./dc.sh artisan $(ARGS)

composer:
	@./dc.sh composer $(ARGS)

npm:
	@./dc.sh npm $(ARGS)

migrate:
	@./dc.sh migrate

test:
	@./dc.sh test

sync:
	@./dc.sh sync
