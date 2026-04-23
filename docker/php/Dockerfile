ARG PHP_VERSION=8.4
ARG DRUPAL_VERSION=11.3
ARG CIVICRM_VERSION=6.12
ARG ENV

FROM drupal:${DRUPAL_VERSION}-php${PHP_VERSION}-fpm

# install the PHP extensions we need
RUN set -eux; \

apt-get update; \
	apt-get install -y --no-install-recommends \
		unzip \       
	;

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
RUN curl -LsS https://download.civicrm.org/cv/cv.phar -o /usr/local/bin/cv \
    && chmod +x /usr/local/bin/cv

ENV COMPOSER_COMPILE=all

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
	# delete composer cache
	rm -rf "$COMPOSER_HOME"    

RUN set -eux; \
	# CiviCRM SES
	cd /opt/drupal/vendor/civicrm/civicrm-core/ext && \
	curl --location -O https://lab.civicrm.org/extensions/ses/-/archive/1.3.15/ses-1.3.15.zip && unzip ses-1.3.15.zip  && \
	curl --location -O https://github.com/systopia/de.systopia.eck/archive/1.0.1.zip && unzip 1.0.1.zip

COPY web/sites/default/settings.php /opt/drupal/web/sites/default/settings.php
COPY web/sites/default/civicrm.settings.php /opt/drupal/web/sites/default/civicrm.settings.php