#!/bin/sh -veux

cv api4 Country.save --in=json < ./config/Country.json
php -d memory_limit=1G -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < ./config/components.json
php -d memory_limit=1G -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < ./config/config.json
cv flush
drush cache:rebuild
cv ext:enable nc_config
cv flush