<?php

$settings = [];

# Paths
$rootPath = dirname(__DIR__);
$settings['rootPath'] = $rootPath;
$settings['rootNamespace'] = "Shared\\Infrastructure\\";
$settings['configPath'] = $rootPath . '/config/';
$settings['tmpPath'] = $rootPath . '/tmp/';
$settings['cachePath'] = $rootPath . '/tmp/cache/';

# Environment
$settings['environment'] = (string) getenv('ENVIRONMENT');

# Container
$settings['di']['container']['path'] = "$rootPath/" . ((string) getenv('PATH_CONTAINER_DEFINITIONS'));
$settings['di']['auto_wires']['path'] = "$rootPath/" . ((string) getenv('PATH_AUTO_WIRES'));
$settings['di']['compiled_passes']['path'] = "$rootPath/" . ((string) getenv('PATH_PASSES_DEFINITION'));
$settings['di']['auto_wires']['enabled'] = ((bool) getenv('ENABLE_AUTO_WIRES'));
$settings['di']['attributes']['enabled'] = ((bool) getenv('ENABLE_ATTRIBUTES'));
$settings['di']['cache']['container']['path'] = "$rootPath/" . ((string) getenv('PATH_COMPILED_CONTAINER'));
$settings['di']['cache']['resolver']['path'] = "$rootPath/" . ((string) getenv('PATH_COMPILED_RESOLVER'));

$settings['test'] = [
    null,
    [],
    [1],
    [1, '1'],
    1 => 1,
    'setting1' => null,
    'setting2' => 1,
    'setting3' => 1.0,
    'setting4' => 'value1',
    'setting5' => [
        '0' => 'value2',
        '1' => [
            '0' => 'value3'
        ]
    ]
];

return $settings;
