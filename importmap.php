<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.4',
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free/css/all.min.css' => [
        'version' => '6.5.1',
        'type' => 'css',
    ],
    '@fortawesome/fontawesome-free' => [
        'version' => '6.5.1',
    ],
    '@symfony/ux-live-component' => [
        'path' => './vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ],
    'idb' => [
        'version' => '7.1.1',
    ],
    'idb-keyval' => [
        'version' => '6.2.2',
    ],
    '@spomky-labs/pwa/helpers' => [
        'path' => './vendor/spomky-labs/pwa-bundle/assets/src/helpers.js',
    ],
    'firebase/app' => [
        'version' => '12.12.1',
    ],
    'firebase/messaging' => [
        'version' => '12.12.1',
    ],
    '@firebase/app' => [
        'version' => '0.14.11',
    ],
    '@firebase/messaging' => [
        'version' => '0.12.25',
    ],
    '@firebase/component' => [
        'version' => '0.7.2',
    ],
    '@firebase/logger' => [
        'version' => '0.5.0',
    ],
    '@firebase/util' => [
        'version' => '1.15.0',
    ],
    '@firebase/installations' => [
        'version' => '0.6.21',
    ],
];
