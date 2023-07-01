cd "/app"

$(which composer) install
$(which php) bin/console doctrine:database:create --if-not-exists
$(which php) bin/console doctrine:schema:update --force --complete
$(which figlet) -f slant "Hello there!"