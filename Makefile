DOCKER=docker-compose

init:
	@if [ ! -f .env ]; then cp .env.example .env; fi
	$(DOCKER) build

up:
	$(DOCKER) up -d

down:
	$(DOCKER) down -v
