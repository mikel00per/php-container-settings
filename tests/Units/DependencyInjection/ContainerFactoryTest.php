<?php

declare(strict_types=1);

namespace Tests\Shared\Infrastructure\Units\DependencyInjection;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shared\Infrastructure\DependencyInjection\ContainerFactory;
use Shared\Infrastructure\DependencyInjection\File;
use Shared\Infrastructure\Environment\Environment;
use Shared\Infrastructure\Settings\SettingsInterface;
use Tests\Shared\Infrastructure\Utils\TestCase;

final class ContainerFactoryTest extends TestCase
{
    private string $settingsPath;
    private ContainerInterface $container;
    private string $containerCachePath;
    private string $containerCacheFilePath;
    private string $containerCacheFileName = 'CompiledContainer.php';

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $rootPath = dirname(__DIR__, 3);
        $this->containerCachePath = "$rootPath/tmp/cache/container";
        $this->containerCacheFilePath = "$this->containerCachePath/$this->containerCacheFileName";
        $this->removeContainerCache();

        $this->settingsPath = __DIR__ . '/../../../config/settings.php';

        $this->container = ContainerFactory::create($this->settingsPath);
    }

    public function tearDown(): void
    {
        $this->removeContainerCache();
    }

    /**
     * @test
     *
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface
     */
    public function it_should_return_an_instance_of_settings(): void
    {
        $this->assertInstanceOf(SettingsInterface::class, $this->container->get(SettingsInterface::class));
    }

    /**
     * @test
     *
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface|Exception
     */
    public function it_should_return_an_instance_of_settings_when_container_is_compiled(): void
    {
        $settings = File::require($this->settingsPath);

        $settings['environment'] = Environment::PRODUCTION->value;
        $settings['di']['cache']['container']['path'] = $this->containerCachePath;

        $containerCompiled = ContainerFactory::create($settings);

        $this->assertInstanceOf(SettingsInterface::class, $containerCompiled->get(SettingsInterface::class));

        $containerCompiled = ContainerFactory::create($settings);
        $this->assertInstanceOf(SettingsInterface::class, $containerCompiled->get(SettingsInterface::class));
    }

    private function removeContainerCache(): void
    {
        @unlink($this->containerCacheFilePath);
        @rmdir($this->containerCachePath);
    }
}
