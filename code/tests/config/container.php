<?php

use Mikelooper\ContainerSettings\Settings;
use Mikelooper\ContainerSettings\SettingsInterface;
use Psr\Container\ContainerInterface;

return [
    'foo' => 'bar',
    SettingsInterface::class => static function (ContainerInterface $container) {
        return new Settings($container->get('settings'));
    }
];
