# Budget Manager API
Symfony based API for a Budget Manager.

## Requirements
Development environment is based on Docker, so make sure you have Docker Engine and Docker Compose installed locally.

## Setup

Go to your local `hosts` file and add `127.0.0.1 budget-manager.api.local` as a new line.  
Location of the `hosts` file:
* Linux and macOS - `/etc/hosts`
* Windows 10 - `c:\Windows\System32\Drivers\etc\hosts`

### Using Just

The easiest way to set up the project is by using the [Just](https://github.com/casey/just) command runner.
Go to the project's root directory and run the `just install` command. This will build up all the needed Docker containers and automatically prepare your local environment.    
Included `Justfile` has a number of useful commands so make sure to check it out.

**NOTE:** When running the `just install` command for the first time for some reason the symfony commands used to creat the DB and update its schema is not
passing and a `exception occurred in the driver: SQLSTATE[HY000] [2002] Connection refused` is thrown. If this happens to you
make sure to run the `just install` command one more time.
### Manual

1. Copy the following provided distribution files:
* `docker-compose.yaml.dist` -> `docker-composer.yaml`
* `env` -> `env.local`
2. run `docker-compose up --build --detach` in order to build up and run needed docker containers.
3. run `docker-compose exec php composer install` in order to install all the required project dependencies.
4. run `docker-compose exec php bin/console doctrine:database:create --if-not-exists` in order to create the MySql DB.
5. run `docker-compose exec php bin/console doctrine:schema:update --force --complete` in order to update the MySql DB.


Now you should be able to access the OpenAPI documentation on http://budget-manager.api.local:8000/api/doc and the
dockerized version of [Adminer](https://www.adminer.org/) database management tool on http://localhost:8080
### Doctrine fixtures

Dummy data can be loaded to the DB by running the `docker-composer exec php bin/console doctrine:fixtures:load`
symfony command. This will create a admin user account that can be used to request a JWT.

#### Admin User Account  
**username:** Admin  
**password:** Password12345!  
**Endpoint for Requesting a JWT:**  [/api/login_check](http://budget-manager.api.local:8000/api/login_check)  
**Request Body:**
```php
{
    "username": "Admin",
    "password": "Password12345!"
}
```

### Authentication

[lexik/jwt-authentication-bundle](https://github.com/lexik/LexikJWTAuthenticationBundle) is used to provide
JWT authentication.

### Running tests
Written test are placed in the [test](https://github.com/ImSmoking/budget-manager/tree/master/tests) folder.  
Before they can be run the test database needs to be set up which can be done by running commands  
`docker-compose exec php bin/console --env=test bin/console doctrine:schema:update --force --complete`,  
`docker-compose exec php bin/console --env=test doctrine:database:create --if-not-exists`, and  
`docker-compose exec php bin/console --env=test doctrine:fixtures:load --quiet`  

Once that is done the tests can be run using the `docker-compose exec php composer run-tests` composer script.