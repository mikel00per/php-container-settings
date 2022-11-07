<?php

namespace Mikelooper\ContainerSettings;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerFactoryTest extends TestCase
{
    private Container $container;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $settingsPath = __DIR__ . '/config/settings.php';

        $this->container = ContainerFactory::buildContainer($settingsPath);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testGetSettings(): void
    {
        $this->assertInstanceOf(Settings::class, $this->container->get(SettingsInterface::class));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testSettingsLevelOne(): void
    {
        $settings = $this->container->get(SettingsInterface::class);

        $this->assertNull($settings->get('test.0'));

        $this->assertIsInt($settings->get('test.1'));

        $this->assertIsArray($settings->get('test.2'));
        $this->assertCount(1, $settings->get('test.2'));

        $this->assertIsArray($settings->get('test.3'));
        $this->assertCount(2, $settings->get('test.3'));

        $this->assertNull($settings->get('test.4'));

        $this->assertNull($settings->get('test.setting1'));

        $this->assertIsInt($settings->get('test.setting2'));

        $this->assertIsFloat($settings->get('test.setting3'));

        $this->assertIsString($settings->get('test.setting4'));

        $this->assertIsArray($settings->get('test.setting5'));
        $this->assertCount(2, $settings->get('test.setting5'));
    }
}
