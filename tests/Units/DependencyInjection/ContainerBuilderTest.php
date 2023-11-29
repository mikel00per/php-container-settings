<?php

declare(strict_types=1);

namespace Tests\Shared\Infrastructure\Units\DependencyInjection;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;
use Shared\Infrastructure\DependencyInjection\CompilerPass;
use Shared\Infrastructure\DependencyInjection\ContainerBuilder;
use Shared\Infrastructure\Resolver\Types;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\Example;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\ExampleInterface;
use Tests\Shared\Infrastructure\Utils\TestCase;

final class ContainerBuilderTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private string $rootNamespace = 'Tests\\Shared\\Infrastructure\\';
    private string $containerCacheFileName = 'CompiledContainer.php';
    private string $containerCachePath;
    private string $containerCacheFilePath;

    public function setUp(): void
    {
        $rootPath = dirname(__DIR__, 3);
        $this->containerCachePath = "$rootPath/tmp/cache/container";
        $this->containerCacheFilePath = "$this->containerCachePath/$this->containerCacheFileName";

        $this->removeContainerCache();

        $this->containerBuilder = ContainerBuilder::create()
            ->addRootNamespace($this->rootNamespace)
            ->addRootPath($rootPath);
    }

    public function tearDown(): void
    {
        $this->removeContainerCache();
    }

    /**
     * @test
     *
     * @throws Exception|ReflectionException|ContainerExceptionInterface|NotFoundExceptionInterface
     */
    public function it_should_build_container_with_resolved_classes(): void
    {
        $classes = $this->containerBuilder->findClassesByResolver(
            ExampleInterface::class,
            Types::INTERFACE,
            'tests',
        );

        $definitions = $this->containerBuilder->findDefinitions($classes);

        $this->containerBuilder->addDefinitions($definitions);

        $this->containerBuilder->enableCompilation($this->containerCachePath);

        $container = $this->containerBuilder->build();

        $this->assertInstanceOf(ExampleInterface::class, $container->get(Example::class));
    }

    /**
     * @test
     *
     * @throws Exception|ContainerExceptionInterface|NotFoundExceptionInterface
     */
    public function it_should_build_compiled_container_with_compiled_pass(): void
    {

        $class = $this->createCompilerPass();

        $this->containerBuilder
            ->enableCompilation($this->containerCachePath)
            ->addCompilerPasses(new $class())
        ;

        $container = $this->containerBuilder->build();

        $this->assertInstanceOf(ExampleInterface::class, $container->get(Example::class));
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function it_should_throw_exception_with_twice_compiled_pass(): void
    {
        $this->expectException(RuntimeException::class);

        $class = $this->createCompilerPass();

        $this->containerBuilder
            ->enableCompilation($this->containerCachePath)
            ->addCompilerPasses(new $class(), new $class())
        ;

        $this->containerBuilder->build();
    }

    private function removeContainerCache(): void
    {
        @unlink($this->containerCacheFilePath);
        @rmdir($this->containerCachePath);
    }

    private function createCompilerPass(): CompilerPass
    {
        return new class implements CompilerPass {

            public function process(ContainerBuilder $containerBuilder): void
            {
                $classes = $containerBuilder->findClassesByResolver(
                    ExampleInterface::class,
                    Types::INTERFACE,
                    'tests',
                );

                $definitions = $containerBuilder->findDefinitions($classes);

                $containerBuilder->addDefinitions($definitions);
            }
        };
    }
}