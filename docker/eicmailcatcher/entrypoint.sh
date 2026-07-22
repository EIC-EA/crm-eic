#!/bin/bash
set -e

mkdir -p "/var/mail/vhosts/${MC_MAIL_DOMAIN}"
mkdir -p /etc/postfix/maps
mkdir -p /var/lib/roundcube

envsubst < /etc/postfix/main.cf.template > /etc/postfix/main.cf

: > /etc/dovecot/users
: > /etc/postfix/maps/vmailbox
: > /etc/postfix/maps/virtual

count=0

#domain cleanup, if they exist:
find /var/mail/vhosts -mindepth 1 -maxdepth 1 -type d ! -name "${MC_MAIL_DOMAIN}" -exec rm -rf {} +
  
IFS=','
for userpass in ${MC_MAIL_USERS}; do
  count=$((count + 1))

  if [ "$count" -gt 10 ]; then
    echo "Maximum 10 users allowed"
    exit 1
  fi

  user="$(echo "$userpass" | cut -d: -f1)"
  pass="$(echo "$userpass" | cut -d: -f2-)"

  if [ -z "$user" ] || [ -z "$pass" ]; then
    echo "Invalid MAIL_USERS entry: $userpass"
    exit 1
  fi

  hash="$(doveadm pw -s SHA512-CRYPT -p "$pass")"

  echo "${user}@${MC_MAIL_DOMAIN}:${hash}" >> /etc/dovecot/users
  echo "${user}@${MC_MAIL_DOMAIN} ${MC_MAIL_DOMAIN}/${user}/Maildir/" >> /etc/postfix/maps/vmailbox

  mkdir -p "/var/mail/vhosts/${MC_MAIL_DOMAIN}/${user}/Maildir"
done
####### Single user mode!
# cat > /etc/postfix/maps/recipient-canonical <<EOF
# /^.+$/ ${CATCHALL_USER}@${MAIL_DOMAIN}
# EOF
#######
####### Multi user mode!

cat > /etc/postfix/maps/relay-domains <<EOF
/^.+$/ OK
EOF

cat > /etc/postfix/maps/recipient-access <<EOF
/^.+$/ OK
EOF

: > /etc/postfix/maps/virtual-alias

if [ "${MC_MAIL_MODE}" = "multi" ]; then
  IFS=','
  for userpass in ${MC_MAIL_USERS}; do
    user="$(echo "$userpass" | cut -d: -f1)"
    escaped_user="$(printf '%s' "$user" | sed 's/[.[\*^$()+?{}|\\]/\\&/g')"
    escaped_domain="$(printf '%s' "$MC_MAIL_DOMAIN" | sed 's/[.[\*^$()+?{}|\\]/\\&/g')"

    echo "/^${escaped_user}@${escaped_domain}$/ ${user}@${MC_MAIL_DOMAIN}" >> /etc/postfix/maps/recipient-canonical
  done
fi

echo "/^.+$/ ${MC_CATCHALL_USER}@${MC_MAIL_DOMAIN}" >> /etc/postfix/maps/recipient-canonical
############################3


chown -R vmail:vmail /var/mail/vhosts

postmap /etc/postfix/maps/vmailbox
# V1:
# postmap /etc/postfix/maps/virtual

MC_ROUNDCUBE_DB="/var/lib/roundcube/roundcube.sqlite"

if [ ! -f "$MC_ROUNDCUBE_DB" ]; then
  sqlite3 "$MC_ROUNDCUBE_DB" < /usr/share/dbconfig-common/data/roundcube/install/sqlite3
fi

chown www-data:www-data "$MC_ROUNDCUBE_DB"

cat > /etc/roundcube/config.inc.php <<EOF
<?php
\$config['db_dsnw'] = 'sqlite:///$MC_ROUNDCUBE_DB';
\$config['imap_host'] = 'localhost:143';
\$config['smtp_host'] = 'localhost:25';
\$config['smtp_user'] = '';
\$config['smtp_pass'] = '';
\$config['product_name'] = '$MC_PRODUCT_NAME';
// change this if needed
\$config['des_key'] = '$MC_ROUNDCUBE_KEY';
EOF

chmod 0644 /etc/postfix/main.cf.template

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
