#!/bin/sh
set -e

case "$XDEBUG_ENABLE" in
  1|true|yes|on)
    DST="$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"
    mv "${DST}.disabled" "${DST}" || true
    echo "[entrypoint] Xdebug ENABLED"
    ;;
esac

case "$ARCLAMP_ENABLE" in
  1|true|yes|on)
    DST="$PHP_INI_DIR/conf.d/zzz_arclamp.ini"
    mv "${DST}.disabled" "${DST}" || true
    echo "[entrypoint] Arc Lamp profiling ENABLED"
    ;;
esac

exec docker-php-entrypoint "$@"