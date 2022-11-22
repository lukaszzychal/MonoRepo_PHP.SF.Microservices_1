
database.drop: ; ./docker/cmd ./bin/console doctrine:database:drop --force --if-exists \
  && ./docker/cmd ./bin/console doctrine:database:drop --force --if-exists --env=test 

database.create: ; ./docker/cmd ./bin/console doctrine:database:create \
  &&  ./docker/cmd ./bin/console doctrine:database:create --env=test 

migration.make: ; ./docker/cmd ./bin/console doctrine:migrations:diff 

migration.run: ; ./docker/cmd ./bin/console doctrine:migrations:migrate  \
	&& ./docker/cmd ./bin/console doctrine:migrations:migrate  --env=test 


test: ; docker/symfony/test/test