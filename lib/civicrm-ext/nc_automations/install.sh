#!/bin/sh -veux

cv flush
drush cache:rebuild
cv ext:enable nc_automations
cv flush