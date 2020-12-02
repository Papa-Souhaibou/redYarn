run:
	sudo symfony server:start
clear:
	php bin/console cache:clear
migration:
	php bin/console make:migration
migrate:
	php bin/console doctrine:migrations:migrate
update:
	php bin/console doctrine:schema:update --force
entity:
	php bin/console make:entity
controller:
	php bin/console make:controller
fixture:
	php bin/console make:fixture
loadAll:
	php bin/console doctrine:fixtures:load --append
greetings:
	echo ${message}