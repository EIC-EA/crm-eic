ARG PHP_VERSION=8.4
ARG DRUPAL_VERSION=11.4.8
ARG ENV

FROM drupal:${DRUPAL_VERSION}-php${PHP_VERSION}-fpm

ARG CIVICRM_VERSION=6.18

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
		gettext \
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
# excimer (sampling profiler) + redis are the arc-lamp client prerequisites.
# arc-lamp's ArcLamp::collect() captures traces via excimer and publishes them
# to a Redis pubsub channel consumed by the (separate) arc-lamp processor.
    pecl install excimer && \
    docker-php-ext-enable excimer && \
    pecl install redis && \
    docker-php-ext-enable redis && \
# xdebug is a PECL extension (not bundled in php-src), so it must be installed
# via pecl, not docker-php-ext-install. docker-php-ext-enable generates the
# correct zend_extension loader ini, which the block below appends settings to.
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    pecl clear-cache&& \
    { \
      echo "xdebug.mode=\${XDEBUG_MODE:-debug,develop}"; \
      echo "xdebug.start_with_request=\${XDEBUG_START_WITH_REQUEST:-yes}"; \
      echo "xdebug.client_host=\${XDEBUG_CLIENT_HOST:-host.docker.internal}"; \
      echo "xdebug.client_port=\${XDEBUG_CLIENT_PORT:-9003}"; \
    } >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" && \
    mv "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
       "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini.disabled" \
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
  && apt-get -y install imagemagick mariadb-client gettext-base

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
	# Install CiviCRM core
	composer require civicrm/civicrm-core:^${CIVICRM_VERSION} && \
	composer config --no-plugins allow-plugins.civicrm/civicrm-asset-plugin true && \
	composer require civicrm/civicrm-packages:^${CIVICRM_VERSION} && \
	composer require civicrm/civicrm-drupal-8:^${CIVICRM_VERSION} && \
	composer require civicrm/cli-tools:^v2026.03.20.1774037652 && \
	# Drush
	composer require drush/drush && \
	# EU Login
	composer require openeuropa/oe_authentication && \
	# Redirect after login
	composer require drupal/user_redirect:^2.0 && \
	# Masquerade
	composer require drupal/masquerade && \
	# Log stdout
	composer require drupal/log_stdout && \
	# Redis for caching
	composer require drupal/redis

# Install CiviCRM extensions
RUN composer config --no-plugins allow-plugins.wikimedia/composer-merge-plugin true && \
	composer config --no-plugins allow-plugins.symfony/runtime true && \
	composer require wikimedia/composer-merge-plugin && \
	composer config extra.merge-plugin.include civicrm-ext/civicrm-ext-composer.json && \
	composer update

COPY lib/civicrm-ext /opt/drupal/civicrm-ext/

# Install CiviCRM extensions (via composer/installers -> civicrm-ext/)
RUN	composer require \
		civicrm-ext/ses \
		civicrm-ext/eck \
		civicrm-ext/configitems \
		civicrm-ext/activitytypeacl \
		civicrm-ext/aip \
		civicrm-ext/form-processor \
		civicrm-ext/sqltasks \
		civicrm-ext/action-provider \
		civicrm-ext/exportpermission \
		civicrm-ext/contactlayout \
		civicrm-ext/civirules \
		civicrm-ext/civicalendar \
		civicrm-ext/advimportformprocessor \
		civicrm-ext/xcm \
		civicrm-ext/advimport \
		civicrm-ext/signatures

	# Apply patches
RUN	composer install && \
	# Copy civicrm static files from /opt/drupal/vendor/civicrm/ to /opt/drupal/web/libraries/civicrm/
	composer civicrm:publish -D && \
	# Delete composer cache
	rm -rf "$COMPOSER_HOME"

COPY docker/php/web/sites/default/settings.php /opt/drupal/web/sites/default/settings.php
COPY docker/php/web/sites/default/civicrm.settings.php /opt/drupal/web/sites/default/civicrm.settings.php
COPY docker/php/docker.ini /usr/local/etc/php/conf.d/zzz_docker.ini
COPY docker/php/deploy.sh /opt/drupal/deploy.sh
COPY docker/php/arclamp-prepend.php /opt/arclamp/arclamp-prepend.php
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY lib/civicrm-ext /opt/drupal/civicrm-ext/
# COPY lib/drupal-modules /opt/drupal/web/modules/custom/
COPY lib/config /opt/drupal/config

# Temporary fix for civicrm issue. Civicrm resolves the extension assets using the extension name instead of the extension key.
RUN cd /opt/drupal/web/libraries/civicrm && \
    ln -s com.agiliway.civicalendar/ civicalendar && \
    ln -s org.civicoop.civirules/ civirules && \
    ln -s org.civicoop.configitems/ configitems && \
    ln -s org.civicrm.contactlayout contactlayout

# Download AWS CA certificate for RDS
RUN mkdir /opt/drupal/ssl/ && curl --output /opt/drupal/ssl/mysql-ca.pem https://truststore.pki.rds.amazonaws.com/global/global-bundle.pem

# Arc Lamp client (profiling): download the Apache-2.0 licensed ArcLamp.php client
# and register the auto_prepend bootstrap. The prepend ini is staged as .disabled
# so profiling is OFF by default; the entrypoint activates it only when
# ARCLAMP_ENABLE is truthy (same pattern as xdebug).
RUN set -eux; \
    curl -LsS \
      https://raw.githubusercontent.com/wikimedia/performance-arc-lamp/master/ArcLamp.php \
      -o /opt/arclamp/ArcLamp.php; \
    echo "auto_prepend_file=/opt/arclamp/arclamp-prepend.php" \
      > "$PHP_INI_DIR/conf.d/zzz_arclamp.ini.disabled"

# Custom entrypoint: conditionally enables Xdebug (XDEBUG_ENABLE) and Arc Lamp
# profiling (ARCLAMP_ENABLE) at container start, then chains to the base image's
# docker-php-entrypoint via `exec docker-php-entrypoint "$@"`.
#
# Declaring a new ENTRYPOINT resets the inherited CMD to null, so the base
# image's CMD ["php-fpm"] must be re-declared here, otherwise "$@" is empty and
# the container exits 0 immediately after setup instead of starting FPM.
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]