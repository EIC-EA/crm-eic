#!/bin/sh -veux

PHP_MEMORY_LIMIT=${PHP_MEMORY_LIMIT:-512M}
CV=/opt/drupal/vendor/bin/cv

#check if export permission is enabled, then enable it.
if ! php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV api4 Extension.get +w 'key=net.ourpowerbase.exportpermission' +w 'status="installed"' +s key | grep -q exportpermission; then
  echo "installing export permission"
  php -d memory_limit=$PHP_MEMORY_LIMIT -f $CV ext:enable exportpermission
else
   echo " export permission is already installed."
fi