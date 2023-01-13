# Makefile for Humanitix Event Connector plugin

all: dist/humanitix-event-connector.zip

# Spin up the development environment
up:
	docker-compose up -d && docker-compose logs -f

# Spin down the development environment
down:
	docker-compose down

# Solidify the database state
# Check that tmp-db.sql doesn't contain the string "humanitix_event_connector_api_key"
# If it does, then show a bit error about how we can't version the secret API key
db:
	docker-compose exec wordpress wp db export - --url=wordpress --allow-root > tmp-db.sql
	@if grep -q "humanitix_event_connector_api_key" tmp-db.sql; then \
		echo "ERROR: The database contains an API key which is a VERY bad idea to commit to GitHub. You have to restart the environment so the datagbase is reset and perform the same setup again, but without setting the API key."; \
		exit 1; \
	fi
	mv tmp-db.sql db.sql

# Package the plugin in a zipfile for easy transport
dist/humanitix-event-connector.zip:
	mkdir -p dist
	zip -r -x@.zipignore dist/humanitix-event-connector.zip ./

# Clean command that removes the file
clean:
	rm -rf dist

.PHONY: up down db clean
