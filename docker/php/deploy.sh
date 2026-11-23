drush status
drush config:status
drush updatedb:status
cv status

drush state:set system.maintenance_mode 1
drush --verbose --debug --yes deploy

# The following command executes functions before DB upgrade and reconcile.
cv --verbose php:eval 'var_export((new CRM_NcConfig_Upgrader())->beforeUpgradeAndReconcile());'

cv --verbose --no-interaction flush --exclude entities
cv --verbose --no-interaction upgrade:db
cv --verbose --no-interaction flush

# The following two MUST BE EXECUTED IN EVERY DEPLOYMENT. They import XCM and FormProcessors.
cv --verbose php:eval 'var_export(load_civicrm_settings_callback());'
cv --verbose php:eval 'var_export(load_form_processor_callback());'

cv --verbose --no-interaction flush

drush state:set system.maintenance_mode 0

drush status
drush config:status
drush updatedb:status
cv status
