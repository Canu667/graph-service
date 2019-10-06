install:
	cp env.dist .env && cp behat.yml.dist behat.yml && composer install

	docker-compose up -d
	docker exec -it graph-app /bin/bash ./wait-for-it.sh db:3306
	docker exec -it graph-app ./bin/console app:create-fixtures

runTests:
	./vendor/bin/behat
