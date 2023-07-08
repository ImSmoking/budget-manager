cd "/app"

$(which php) bin/console --env=test doctrine:database:create --if-not-exists --complete
$(which php) bin/console --env=test doctrine:schema:update --force --complete
$(which php) bin/console --env=test doctrine:fixtures:load --quiet