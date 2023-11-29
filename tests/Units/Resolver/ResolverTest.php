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
    private string $rootPath;
    private string $resolverCacheDir;
    private string $resolverCachePathFile;
    private string $rootNamespace = 'Tests\\Shared\\Infrastructure\\';
    private string $resolverFileName = 'example_cache.php';

    public function setUp(): void
    {
        parent::setUp();

        $this->rootPath = dirname(__DIR__, 3);
        $this->resolverCacheDir = "$this->rootPath/tmp/cache/container/resolved_classes";
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
            $this->rootPath
        );

        $this->assertEqualsCanonicalizing([Example::class, ExampleImplement::class], $actual);
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
            $this->rootPath,
            $this->resolverCachePathFile
        );

        $actual = $resolver->resolve(
            ExampleInterface::class,
            Types::INTERFACE,
            'tests',
            $this->rootNamespace,
            $this->rootPath,
            $this->resolverCachePathFile
        );

        $this->assertEqualsCanonicalizing([Example::class, ExampleImplement::class], $actual);
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
            $this->rootPath
        );

        $this->assertEqualsCanonicalizing([ExampleWithAttribute::class], $actual);
    }
}