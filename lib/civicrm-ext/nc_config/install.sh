#!/bin/sh -veux

PHP_MEMORY_LIMIT=${PHP_MEMORY_LIMIT:-512M}
CV=/opt/drupal/vendor/bin/cv

cv api4 Country.save --in=json < ./config/Country.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/components.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/config.json


cv flush
drush cache:rebuild
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV ext:enable nc_config
cv flush