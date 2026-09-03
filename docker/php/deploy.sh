drush status
drush config:status
drush updatedb:status
cv status -v 

drush state:set system.maintenance_mode 1
drush --verbose --debug deploy
cv upgrade:db
cv flush
drush state:set system.maintenance_mode 0

drush status
drush config:status
drush updatedb:status
cv status -v