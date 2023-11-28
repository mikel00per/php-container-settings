<?php

declare(strict_types=1);

namespace Shared\Infrastructure\DependencyInjection;

use Exception;
use Psr\Container\ContainerInterface;
use Shared\Infrastructure\Environment\Environment;

final readonly class ContainerFactory
{
    private function __construct() {}

    /**
     * @throws Exception
     */
    public static function create(array|string $settings): ContainerInterface
    {
        // 1. Required files
        $settingsArray = is_array($settings) ?
            $settings :
            File::require($settings, 'Settings file does not exist');

        $containerPath = $settingsArray['di']['container']['path'];
        $containerDefinitions = File::require($containerPath, 'Container path does not exist');

        $compiledPassesPath = $settingsArray['di']['compiler_passes']['path'];
        $compiledPassDefinitions = File::require($compiledPassesPath, 'Compiled passes file does not exist');

        $autoWiresPath = $settingsArray['di']['auto_wires']['path'];
        $autoWiresDefinitions = File::require($autoWiresPath, 'Auto wires file does not exist');

        // 2. Crete container
        $containerBuilder = ContainerBuilder::create()
            ->addSettingsArray($settingsArray)
            ->addRootPath($settingsArray['rootPath'])
            ->addRootNamespace($settingsArray['rootNamespace'])
            ->useAutoWiring($settingsArray['di']['auto_wires']['enabled'])
            ->useAttributes($settingsArray['di']['attributes']['enabled'])
        ;

        // 3. Cache
        if (Environment::from($settingsArray['environment']) === Environment::PRODUCTION) {
            $containerCachePath = $settingsArray['di']['cache']['container']['path'];
            $resolverCachePathFile = $settingsArray['di']['cache']['resolver']['path'];

            $containerBuilder
                ->enableCompilation($containerCachePath)
                ->addResolverCachePathFile($resolverCachePathFile)
                ->addDefinitions($autoWiresDefinitions);
        }

        // 4. Add definitions
        $containerBuilder
            ->addSettingsDefinition($containerDefinitions)
            ->addDefinitions($containerDefinitions)
            ->addCompilerPasses(...$compiledPassDefinitions);

        return $containerBuilder->build();
    }
}
