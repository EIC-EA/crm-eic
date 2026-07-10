#!/bin/sh -veux

PHP_MEMORY_LIMIT=${PHP_MEMORY_LIMIT:-1G}
CV=/opt/drupal/vendor/bin/cv
cv api4 Country.save --in=json < ./config/Country.json

php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/components.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/config.json

#mailing part:
if ! cv api4 Extension.get +w 'key=civi_mail' +w 'status="installed"' +s key | grep -q civi_mail; then
  cv ext:enable civi_mail
fi

if [ "${SES_ENABLED:-false}" = "true" ]; then
  echo "Enabling and configuring SES extension..."
  if ! cv api4 Extension.get +w 'key=ses' +w 'status="installed"' +s key | grep -q ses; then
    cv ext:enable ses
  fi
#  envsubst < ./config/ses.json.template > /tmp/ses.json
#  php -d memory_limit=$PHP_MEMORY_LIMIT -f /opt/drupal/vendor/bin/cv api4 Setting.set --in=json < /tmp/ses.json
fi

envsubst < ./config/smtp.json.template > /tmp/smtp.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < /tmp/smtp.json
envsubst < ./config/site-email.json.template > /tmp/site-email.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 SiteEmailAddress.update --in=json < /tmp/site-email.json




#### TO DO, WHAT ON PROD
MAIL_WHERE="name = \"$MC_IMAP_NAME\""
MAIL_SETTINGS_ID="$(
  php -d memory_limit="$PHP_MEMORY_LIMIT" \
    -f "$CV" \
    api4 MailSettings.get \
    +w "$MAIL_WHERE" \
    +select=id \
    --out=json |
  php -r '
    $result = json_decode(stream_get_contents(STDIN), true);
    echo $result[0]["id"] ?? "";
  '
)"

if [ -n "$MAIL_SETTINGS_ID" ]; then
  export MC_MAIL_SETTINGS_ID="$MAIL_SETTINGS_ID"
  envsubst < ./config/mail-account-update.json.template > /tmp/mail-account.json
  php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 MailSettings.update --in=json < /tmp/mail-account.json
else
  envsubst < ./config/mail-account.json.template > /tmp/mail-account.json
  php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 MailSettings.create --in=json < /tmp/mail-account.json
fi

cv flush
drush cache:rebuild
cv ext:enable nc_config
cv flush
