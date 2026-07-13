# crm-common
Common code for the CRM

drush sql:drop
drush site-install --site-name=srm --account-name=admin --account-pass=admin -y
php -d memory_limit=2G -f /opt/drupal/vendor/bin/cv -- core:install --url=$CIVICRM_UF_BASEURL --db="mysql://$DRUPAL_DATABASE_USERNAME:$DRUPAL_DATABASE_PASSWORD@$DRUPAL_DATABASE_HOST:$DRUPAL_DATABASE_PORT/$DRUPAL_DATABASE_NAME" -f

cv ext:enable eic_config

# Then in Drupal Admin > Appearance, CiviCRM > CiviCRM Administration theme > Claro 