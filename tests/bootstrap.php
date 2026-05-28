<?php

$current_plugin_folder = basename(realpath(__DIR__ . '/../'));

require __DIR__ . '/../../../tests/bootstrap.php';
require dirname(__DIR__) . '/vendor/autoload.php';

if (!Plugin::isPluginActive($current_plugin_folder)) {
    throw new RuntimeException("Plugin $current_plugin_folder is not active in the test database");
}
