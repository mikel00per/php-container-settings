<?php

use ContainerSettings\Settings;
use ContainerSettings\SettingsInterface;
use Psr\Container\ContainerInterface;

return [
    'foo' => 'bar',
    SettingsInterface::class => static function (ContainerInterface $container) {
        return new Settings($container->get('settings'));
    }
];
