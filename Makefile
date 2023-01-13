# Makefile for Humanitix Event Connector plugin

all: dist/humanitix-event-connector.zip

# Spin up the development environment
up:
	docker-compose up -d && docker-compose logs -f

# Spin down the development environment
down:
	docker-compose down

# Solidify the database state
db:
	docker-compose exec wordpress wp db export - --url=wordpress --allow-root > db.sql

# Package the plugin in a zipfile for easy transport
dist/humanitix-event-connector.zip:
	mkdir -p dist
	zip -r -x@.zipignore dist/humanitix-event-connector.zip ./

# Clean command that removes the file
clean:
	rm -rf dist

.PHONY: up down db clean
