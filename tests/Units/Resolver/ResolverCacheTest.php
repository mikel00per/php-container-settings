<?php

namespace Tests\Shared\Infrastructure\Units\Resolver;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use RuntimeException;
use Shared\Infrastructure\Resolver\ResolverCache;
use Tests\Shared\Infrastructure\Units\__resources__\Resolver\Interfaces\ExampleInterface;

final class ResolverCacheTest extends TestCase
{
    private string $dir;
    private string $pathCacheFile;

    public function setUp(): void
    {
        parent::setUp();

        $file = 'example_cache.php';

        $rootPath = dirname(__DIR__, 3);
        $this->dir = "$rootPath/tmp/cache/container/resolved_classes";
        $this->pathCacheFile = "$this->dir/$file";

        @unlink($this->pathCacheFile);
        @rmdir($this->dir);
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function it_should_throw_exception_because_cache_path_not_exist(): void
    {
        $this->expectException(RuntimeException::class);

        $resolveCache = new ResolverCache(ExampleInterface::class, '/no/exist');

        $resolveCache->get();
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function it_should_throw_exception_because_cache_path_can_not_write(): void
    {
        $this->expectException(RuntimeException::class);

        $resolveCache = new ResolverCache(ExampleInterface::class, '/');

        $resolveCache->get();
    }

    public function it_should_compile(): void
    {
        $resolveCache = new ResolverCache(ExampleInterface::class, $this->pathCacheFile);

        $this->assertFalse($resolveCache->exists());
        $resolveCache->compile(['classOne', 'classTwo']);
        $path = $resolveCache->compile(['classOne', 'classTwo']);
        $this->assertEquals($path, $this->pathCacheFile);
        $this->assertTrue($resolveCache->exists());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->pathCacheFile);
        @rmdir($this->dir);
    }
}