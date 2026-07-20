ARG PHP_VERSION=8.4
ARG DRUPAL_VERSION=11.3
ARG CIVICRM_VERSION=6.12
ARG ENV

FROM drupal:${DRUPAL_VERSION}-php${PHP_VERSION}-fpm

# install the PHP extensions we need
RUN set -eux; \

apt-get update; \
	apt-get install -y --no-install-recommends \
		unzip;

# install the PHP extensions we need
RUN set -eux; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libicu-dev \
		imagemagick \
        libmagickwand-dev \
        mariadb-client \        
	; \
	\
	docker-php-ext-install -j "$(nproc)" \
		bcmath \
		intl \
		mysqli \
	; \
	\
# imagick is required for eg. Mosaico
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    pecl clear-cache \
	; \
	\
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
		| awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); printf "*%s\n", so }' \
		| sort -u \
		| xargs -r dpkg-query -S \
		| cut -d: -f1 \
		| sort -u \
		| xargs -rt apt-mark manual; \
	\
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

RUN apt-get -o Acquire::Check-Valid-Until=false -o Acquire::Check-Date=false update \
  && apt-get -y install imagemagick mariadb-client

# Download cv (https://github.com/civicrm/cv)
#RUN curl -LsS https://download.civicrm.org/cv/cv.phar -o /usr/local/bin/cv \
#    && chmod +x /usr/local/bin/cv

ENV COMPOSER_COMPILE=all

COPY lib/patches /opt/drupal/patches

RUN set -eux; \
	export COMPOSER_HOME="$(mktemp -d)"; \
# https://github.com/docker-library/drupal/pull/266#issuecomment-2273985526
	composer check-platform-reqs; \
	# Enable patching
	composer config extra.enable-patching true && \
	composer config extra.patches-file "./patches/composer.patches.json" && \
	# Allow required plugins
	composer config --no-plugins allow-plugins.cweagans/composer-patches true && \
	composer config --no-plugins allow-plugins.civicrm/composer-downloads-plugin true && \
	composer config --no-plugins allow-plugins.civicrm/composer-compile-plugin true && \
	composer config --no-plugins allow-plugins.composer/installers true && \
	# Set install path for civicrm-ext type packages
	composer config extra.installer-paths.civicrm-ext/{\$name} '["type:civicrm-ext"]' --json && \
	# Define CiviCRM extensions as package repositories
	composer config repositories.ses '{"type":"package","package":{"name":"civicrm-ext/ses","version":"1.3.15","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/ses/-/archive/1.3.15/ses-1.3.15.zip","type":"zip"}}}' --json && \
	composer config repositories.eck '{"type":"package","package":{"name":"civicrm-ext/eck","version":"1.0.1","type":"civicrm-ext","dist":{"url":"https://github.com/systopia/de.systopia.eck/archive/1.0.1.zip","type":"zip"}}}' --json && \
	composer config repositories.configitems '{"type":"package","package":{"name":"civicrm-ext/configitems","version":"2.0.0","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/configitems/-/archive/2.0.0/configitems-2.0.0.zip","type":"zip"}}}' --json && \
	composer config repositories.activitytypeacl '{"type":"package","package":{"name":"civicrm-ext/activitytypeacl","version":"1.6.1","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/activitytypeacl/-/archive/1.6.1/activitytypeacl-1.6.1.zip","type":"zip"}}}' --json && \
	composer config repositories.aip '{"type":"package","package":{"name":"civicrm-ext/aip","version":"1.3.0","type":"civicrm-ext","dist":{"url":"https://github.com/systopia/aip/releases/download/1.3.0/aip-1.3.0.zip","type":"zip"}}}' --json && \
	composer config repositories.form-processor '{"type":"package","package":{"name":"civicrm-ext/form-processor","version":"3.2.0","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/form-processor/-/archive/3.2.0/form-processor-3.2.0.zip","type":"zip"}}}' --json && \
	composer config repositories.sqltasks '{"type":"package","package":{"name":"civicrm-ext/sqltasks","version":"2.2.0","type":"civicrm-ext","dist":{"url":"https://github.com/systopia/de.systopia.sqltasks/releases/download/2.2.0/de.systopia.sqltasks-2.2.0.zip","type":"zip"}}}' --json && \
	composer config repositories.action-provider '{"type":"package","package":{"name":"civicrm-ext/action-provider","version":"1.196","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/action-provider/-/archive/1.196/action-1.196.zip","type":"zip"}}}' --json && \
	composer config repositories.exportpermission '{"type":"package","package":{"name":"civicrm-ext/exportpermission","version":"1.4","type":"civicrm-ext","dist":{"url":"https://github.com/progressivetech/net.ourpowerbase.exportpermission/archive/1.4.zip","type":"zip"}}}' --json && \
	composer config repositories.contactlayout '{"type":"package","package":{"name":"civicrm-ext/contactlayout","version":"2.4.0","type":"civicrm-ext","dist":{"url":"https://github.com/civicrm/org.civicrm.contactlayout/archive/2.4.0.zip","type":"zip"}}}' --json && \
	composer config repositories.civirules '{"type":"package","package":{"name":"civicrm-ext/civirules","version":"3.38.0","type":"civicrm-ext","dist":{"url":"https://lab.civicrm.org/extensions/civirules/-/archive/3.38.0/civirules-3.38.0.zip","type":"zip"}}}' --json && \
	# Install CiviCRM core
	composer require civicrm/civicrm-core:^6.12 && \
	composer config --no-plugins allow-plugins.civicrm/civicrm-asset-plugin true && \
	composer require civicrm/civicrm-packages:^6.12 && \
	composer require civicrm/civicrm-drupal-8:^6.12 && \
	composer require civicrm/cli-tools:^v2025.12.06.1764981525 && \
	# Drush
	composer require drush/drush && \
	# EU Login
	composer require openeuropa/oe_authentication && \
	# Redirect after login
	composer require drupal/user_redirect:^2.0 && \
	# Masquerade
	composer require drupal/masquerade && \
	# Install CiviCRM extensions (via composer/installers -> civicrm-ext/)
	composer require \
		civicrm-ext/ses:1.3.15 \
		civicrm-ext/eck:1.0.1 \
		civicrm-ext/configitems:2.0.0 \
		civicrm-ext/activitytypeacl:1.6.1 \
		civicrm-ext/aip:1.3.0 \
		civicrm-ext/form-processor:3.2.0 \
		civicrm-ext/sqltasks:2.2.0 \
		civicrm-ext/action-provider:1.196 \
		civicrm-ext/exportpermission:1.4 \
		civicrm-ext/contactlayout:2.4.0 \
		civicrm-ext/civirules:3.38.0 && \
	# Apply patches
	composer install && \
	# Copy civicrm static files from /opt/drupal/vendor/civicrm/ to /opt/drupal/web/libraries/civicrm/
	composer civicrm:publish -D && \
	# Delete composer cache
	rm -rf "$COMPOSER_HOME"

COPY docker/php/web/sites/default/settings.php /opt/drupal/web/sites/default/settings.php
COPY docker/php/web/sites/default/civicrm.settings.php /opt/drupal/web/sites/default/civicrm.settings.php
COPY docker/php/docker.ini /usr/local/etc/php/conf.d/zzz_docker.ini

COPY lib/civicrm-ext /opt/drupal/civicrm-ext/
COPY lib/drupal-modules /opt/drupal/web/modules/custom/
COPY lib/config /opt/drupal/config

#"extra": {
#"civicrm-asset": {
#"assets:packages": {
#"+include": [
#"kcfinder/**"