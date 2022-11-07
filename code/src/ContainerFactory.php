<?php

declare(strict_types=1);

namespace Mikelooper\ContainerSettings;

use DI\Container;
use DI\ContainerBuilder;
use Exception;

final class ContainerFactory implements ContainerBuilderInterface
{
    private const PRODUCTION = 'production';

    private function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public static function buildContainer(string $pathSettings): Container
    {
        $containerBuilder = new ContainerBuilder();

        /** @var array<string,array<string, array<string, string>>> $settings */
        $data = require $pathSettings;

        /** @var array $definitions */
        $definitions = require $data['container']['definitions'];

        $definitions['settings'] = $data;

        $containerCacheEnabled = $data['container']['cache']['enabled'];
        $containerCachePath = $data['container']['cache']['path'];

        $containerBuilder->addDefinitions($definitions);

        if ($containerCacheEnabled) {
            $containerBuilder->enableCompilation($containerCachePath);
        }

        // Build PHP-DI Container instance
        return $containerBuilder->build();
    }
}
