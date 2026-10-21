drush status
drush config:status
drush updatedb:status
cv status

drush state:set system.maintenance_mode 1
drush --verbose --debug --yes deploy

cv --verbose php:eval 'var_export((new CRM_NcConfig_Upgrader())->___upgrade_1005());'


cv --verbose --no-interaction flush
cv --verbose --no-interaction upgrade:db
cv --verbose --no-interaction flush
drush state:set system.maintenance_mode 0

drush status
drush config:status
drush updatedb:status
cv status
