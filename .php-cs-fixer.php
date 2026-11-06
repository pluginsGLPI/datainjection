<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$baseline_file = __DIR__ . '/../../PluginsPhpCsFixer.php';
if (!file_exists($baseline_file)) {
    throw new RuntimeException(
        sprintf(
            'Unable to find "%s". Running php-cs-fixer on a plugin requires a GLPI development checkout that ships PluginsPhpCsFixer.php.',
            $baseline_file,
        ),
    );
}

$baseline = require $baseline_file;

$finder = Finder::create()
    ->in(__DIR__)
    ->ignoreVCSIgnored(true)
    ->name('*.php');

/** @var Config $config */
$config = $baseline($finder);

return $config
    ->setCacheFile(__DIR__ . '/var/php-cs-fixer/.php-cs-fixer.cache')
;
