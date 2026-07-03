#!/bin/sh -veux

cv api4 Country.save --in=json < ./config/Country.json
php -d memory_limit=$PHP_MEMORY_LIMITG -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < ./config/components.json
php -d memory_limit=8$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < ./config/config.json

#mailing part:
if ! cv api4 Extension.get +w 'key=civi_mail' +w 'status="installed"' +s key | grep -q civi_mail; then
  cv ext:enable civi_mail
fi

if [ "${SES_ENABLED:-false}" = "true" ]; then
  echo "Enabling and configuring SES extension..."
  if ! cv api4 Extension.get +w 'key=ses' +w 'status="installed"' +s key | grep -q ses; then
    cv ext:enable ses
  fi
  envsubst < ./config/ses.json.template > /tmp/ses.json
  php -d memory_limit=$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < /tmp/ses.json
fi

envsubst < ./config/smtp.json.template > /tmp/smtp.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < /tmp/smtp.json
envsubst < ./config/site-email.json.template > /tmp/site-email.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 SiteEmailAddress.update --in=json < /tmp/site-email.json
envsubst < ./config/mail-account.json.template > /tmp/mail-account.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 MailSettings.create --in=json < /tmp/mail-account.json

cv flush
drush cache:rebuild
cv ext:enable nc_config
cv flush
