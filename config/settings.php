<?php

$settings = [];

# Paths
$settings['root'] = dirname(__DIR__);
$settings['config'] = $settings['root'] . '/config/';
$settings['tmp'] = $settings['root'] . '/tmp/';
$settings['cache'] = $settings['root'] .'/tmp/cache/';

# Container
$settings['container'] = [
    'definitions' => $settings['config'] . ((string) getenv('PATH_CONTAINER_DEFINITION')),
    'cache' => [
        'enabled' => ((bool) getenv('COMPILE_CONTAINER')),
        'path' => $settings['cache'] . ((string) getenv('PATH_CONTAINER_COMPILATION'))
    ]
];

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
