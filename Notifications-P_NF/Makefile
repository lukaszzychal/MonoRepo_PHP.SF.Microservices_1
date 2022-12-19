
database.drop: ; ./docker/cmd ./bin/console -n doctrine:database:drop --force --if-exists \
  && ./docker/cmd ./bin/console -n doctrine:database:drop --force --if-exists --env=test 

database.create: ; ./docker/cmd ./bin/console -n doctrine:database:create \
  &&  ./docker/cmd ./bin/console -n doctrine:database:create --env=test 

migration.make: ; ./docker/cmd ./bin/console -n doctrine:migrations:diff 

migration.run: ; ./docker/cmd ./bin/console -n doctrine:migrations:migrate  \
	&& ./docker/cmd ./bin/console -n doctrine:migrations:migrate  --env=test 

migration.run.full: ; make database.drop database.create migration.run

test: ; docker/symfony/test/test