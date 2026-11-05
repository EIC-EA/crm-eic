<?php
/**
 * Arc Lamp auto_prepend bootstrap.
 *
 * Loaded via auto_prepend_file (see arclamp.ini). It starts the Arc Lamp
 * sampling client, which uses the excimer extension to periodically capture a
 * stack trace and publish it to a Redis pubsub channel ("excimer"). A separate
 * arc-lamp processor service consumes that channel and renders flame graphs.
 *
 * This bootstrap is a no-op unless the excimer extension is loaded and the
 * ArcLamp client class is available, so it is safe to leave prepended.
 *
 * Configuration (environment variables):
 *   REDIS_HOST     Host to publish samples to (default: valkey)
 *   REDIS_PORT     Port (default: 6379)
 *   ARCLAMP_PERIOD Sampling interval in seconds (default: 60)
 *
 * Whether this file is prepended at all is controlled at the container level:
 * the entrypoint only activates arclamp.ini when ARCLAMP_ENABLE is truthy.
 */

$arclampClient = '/opt/arclamp/ArcLamp.php';

// Only engage when the profiler extension and the client are both present.
if (extension_loaded('excimer') && is_readable($arclampClient)) {
    require_once $arclampClient;

    if (class_exists(\Wikimedia\ArcLamp::class)) {
        $redisHost = getenv('REDIS_HOST') ?: 'valkey';
        $redisPort = getenv('REDIS_PORT') ?: 6379;
        $period    = getenv('ARCLAMP_PERIOD') ?: 60;

        \Wikimedia\ArcLamp::collect([
            'redis-host'     => $redisHost,
            'redis-port'     => (int) $redisPort,
            'excimer-period' => (float) $period,
        ]);
    }
}
