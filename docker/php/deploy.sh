drush status
drush config:status
drush updatedb:status
cv status

drush state:set system.maintenance_mode 1
drush --verbose --debug deploy
cv --verbose --no-interaction upgrade:db
cv --verbose --no-interaction flush
drush state:set system.maintenance_mode 0

drush status
drush config:status
drush updatedb:status
cv status
