.PHONY: help init dev up down build

help:
	@echo "Available commands:"
	@echo "  make init      - Initialize .env and install dependencies"
	@echo "  make up        - Start all services with Docker Compose"
	@echo "  make down      - Stop all Docker Compose services"
	@echo "  make build     - Rebuild Docker Compose images"

init:
	@bash init.sh

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build
