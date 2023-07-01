# Load variables from .env file
set dotenv-load := true

default:
	@echo 'Documentation for Symfony Skeleton just commands:'
	@echo '--------------------------------------------------------------------'
	@echo '  up             : boot up containers'
	@echo '  down           : stop containers'
	@echo '  enter-php      : enter PHP docker container'
	@echo '  enter-db       : enter MySql DB docker container'
	@echo '  enter-nginx    : enter Nginx docker container'
	@echo '  install        : install and configure the whole skeleton project'
	@echo '  teardown       : remove everything and reset to factory settings'
	@echo '--------------------------------------------------------------------'

up:
    docker-compose up -d

down:
    docker-compose stop

enter-php:
    @echo 'Enter PHP docker container...'
    docker exec -it ${PROJECT}_php /bin/bash

enter-db:
    @echo 'Enter MySql DB docker container...'
    docker exec -it ${PROJECT}_db /bin/bash

enter-nginx:
    @echo 'Enter Nginx docker container...'
    docker exec -it ${PROJECT}_nginx /bin/bash

install:
    @echo 'Running the installation script, this might take a minute...'
    cp docker-compose.yaml.dist docker-compose.yaml
    cp .env .env.local
    docker-compose up --build --detach --force-recreate
    docker-compose exec php /bin/bash /home/appuser/setup.sh

teardown:
    #!/usr/bin/env bash
    echo "This will delete everything and restore the project to its factory settings."
    echo -n "Are you sure you want to do this? [y/N]: " && read ans && if [[ $ans != y ]]; then exit; fi
    docker-compose stop
    docker-compose rm -v --force
    rm -rf vendor
    rm docker-compose.yaml
    rm .env.local