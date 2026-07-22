#!/bin/sh -veux

PHP_MEMORY_LIMIT=${PHP_MEMORY_LIMIT:-1G}
CV=/opt/drupal/vendor/bin/cv
cv api4 Country.save --in=json < ./config/Country.json

php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/components.json
php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Setting.set --in=json < ./config/config.json



#### OLD APPROACH NOT USED ANYMORE
# MAIL_WHERE="name = \"$MC_IMAP_NAME\""
# MAIL_SETTINGS_ID="$(
#   php -d memory_limit="$PHP_MEMORY_LIMIT" \
#     -f "$CV" \
#     api4 MailSettings.get \
#     +w "$MAIL_WHERE" \
#     +select=id \
#     --out=json |
#   php -r '
#     $result = json_decode(stream_get_contents(STDIN), true);
#     echo $result[0]["id"] ?? "";
#   '
# )"

# if [ -n "$MAIL_SETTINGS_ID" ]; then
#   export MC_MAIL_SETTINGS_ID="$MAIL_SETTINGS_ID"
#   envsubst < ./config/mail-account-update.json.template > /tmp/mail-account.json
#   php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 MailSettings.update --in=json < /tmp/mail-account.json
# else
#   envsubst < ./config/mail-account.json.template > /tmp/mail-account.json
#   php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 MailSettings.create --in=json < /tmp/mail-account.json
# fi

cv flush
drush cache:rebuild
cv ext:enable nc_config
cv flush
