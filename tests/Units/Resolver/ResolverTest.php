<?php

namespace Tests\Shared\Infrastructure\Units\Resolver;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use Shared\Infrastructure\Resolver\Resolver;
use Shared\Infrastructure\Resolver\Types;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Attributes\ExampleAttribute;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Attributes\Example as ExampleWithAttribute;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\Example;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\ExampleImplement;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\ExampleInterface;

final class ResolverTest extends TestCase
{
    private string $rootDirectory = '/code';
    private string $rootNamespace = 'Tests\\Shared\\Infrastructure\\';
    private string $resolverCacheDir = '/code/tmp/cache/container/resolved_classes';
    private string $resolverFileName = 'example_cache.php';
    private string $resolverCachePathFile;

    public function setUp(): void
    {
        parent::setUp();

        $this->resolverCachePathFile = "$this->resolverCacheDir/$this->resolverFileName";

        $this->removeCacheFolder();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->removeCacheFolder();
    }

    public function removeCacheFolder(): void
    {
        @unlink($this->resolverCachePathFile);
        @rmdir($this->resolverCacheDir);
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function it_should_can_resolve_interfaces(): void
    {
        $resolver = new Resolver();

        $actual = $resolver->resolve(
            ExampleInterface::class,
            Types::INTERFACE,
            'tests',
            $this->rootNamespace,
            $this->rootDirectory
        );

        $this->assertEquals([ExampleImplement::class, Example::class], $actual);
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function it_should_can_resolve_interfaces_cache(): void
    {
        $resolver = new Resolver();

        $resolver->resolve(
            ExampleInterface::class,
            Types::INTERFACE,
            'tests',
            $this->rootNamespace,
            $this->rootDirectory,
            $this->resolverCachePathFile
        );

        $actual = $resolver->resolve(
            ExampleInterface::class,
            Types::INTERFACE,
            'tests',
            $this->rootNamespace,
            $this->rootDirectory,
            $this->resolverCachePathFile
        );

        $this->assertEquals([ExampleImplement::class, Example::class], $actual);
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function it_should_can_resolve_attributes(): void
    {
        $resolver = new Resolver();

        $actual = $resolver->resolve(
            ExampleAttribute::class,
            Types::ATTRIBUTE,
            'tests',
            $this->rootNamespace,
            $this->rootDirectory
        );

        $this->assertEquals([ExampleWithAttribute::class], $actual);
    }
}