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

RUN sed -i -e 's/"extra": {/"extra": { "patches-file": ".\/patches\/composer.patches.json",/' composer.json
COPY lib/patches /opt/drupal/patches

RUN set -eux; \
	export COMPOSER_HOME="$(mktemp -d)"; \
# https://github.com/docker-library/drupal/pull/266#issuecomment-2273985526
	composer check-platform-reqs; \    
    composer config extra.enable-patching true && \
# required by civicrm-core
    composer config --no-plugins allow-plugins.cweagans/composer-patches true && \
    composer config --no-plugins allow-plugins.civicrm/composer-downloads-plugin true && \
    composer config --no-plugins allow-plugins.civicrm/composer-compile-plugin true && \
    composer require civicrm/civicrm-core:^6.12 && \
    composer config --no-plugins allow-plugins.civicrm/civicrm-asset-plugin true && \
    composer require civicrm/civicrm-packages:^6.12 && \
    composer require civicrm/civicrm-drupal-8:^6.12 && \
    composer require civicrm/cli-tools:^v2025.12.06.1764981525 && \
	# copy civicrm static files from /opt/drupal/vendor/civicrm/ to /opt/drupal/web/libraries/civicrm/
	composer civicrm:publish -D && \
	# drush
	composer require drush/drush && \
	# EU Login
	composer require openeuropa/oe_authentication  && \
	# Redirect after login 
	composer require drupal/user_redirect:^2.0 && \
	# Apply patches
	composer install && \
	# delete composer cache
	rm -rf "$COMPOSER_HOME"    

RUN set -eux; \
	# CiviCRM SES
	mkdir /opt/drupal/civicrm-ext && \
	cd /opt/drupal/civicrm-ext && \
	curl --location -O https://lab.civicrm.org/extensions/ses/-/archive/1.3.15/ses-1.3.15.zip && unzip ses-1.3.15.zip  && rm ses-1.3.15.zip && \
	curl --location -O https://github.com/systopia/de.systopia.eck/archive/1.0.1.zip && unzip 1.0.1.zip && rm 1.0.1.zip && \
	curl --location -O https://lab.civicrm.org/extensions/configitems/-/archive/2.0.0/configitems-2.0.0.zip && unzip configitems-2.0.0.zip && rm configitems-2.0.0.zip && \
	curl --location -O https://lab.civicrm.org/extensions/activitytypeacl/-/archive/1.6.1/activitytypeacl-1.6.1.zip && unzip activitytypeacl-1.6.1.zip && rm activitytypeacl-1.6.1.zip && \
	curl --location -O https://github.com/systopia/aip/releases/download/1.3.0/aip-1.3.0.zip && unzip aip-1.3.0.zip && rm aip-1.3.0.zip && \
	curl --location -O https://lab.civicrm.org/extensions/form-processor/-/archive/3.2.0/form-processor-3.2.0.zip && unzip form-processor-3.2.0.zip && rm form-processor-3.2.0.zip && \
	curl --location -O https://github.com/systopia/de.systopia.sqltasks/releases/download/2.2.0/de.systopia.sqltasks-2.2.0.zip && unzip de.systopia.sqltasks-2.2.0.zip && rm de.systopia.sqltasks-2.2.0.zip && \
	curl --location -O https://lab.civicrm.org/extensions/action-provider/-/archive/1.196/action-1.196.zip && unzip action-1.196.zip && rm action-1.196.zip

COPY docker/php/web/sites/default/settings.php /opt/drupal/web/sites/default/settings.php
COPY docker/php/web/sites/default/civicrm.settings.php /opt/drupal/web/sites/default/civicrm.settings.php

COPY lib/civicrm-ext /opt/drupal/civicrm-ext/
COPY lib/config /opt/drupal/config

#"extra": {
#"civicrm-asset": {
#"assets:packages": {
#"+include": [
#"kcfinder/**"
