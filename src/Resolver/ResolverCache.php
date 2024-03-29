<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Resolver;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

final class ResolverCache
{
    private string $cacheFileName;

    /**
     * @param class-string $toResolve
     */
    public function __construct(
        private readonly string $toResolve,
        private readonly string $pathCacheFile,
    ) {
        $this->cacheFileName = rtrim($this->pathCacheFile, '/');
    }

    /**
     * @throws ReflectionException
     */
    public function get(): string
    {
        if (!$this->exists()) {
            throw new RuntimeException(sprintf(
                'Cache not set for %s',
                (new ReflectionClass($this->toResolve))->getShortName()
            ));
        }

        return $this->cacheFileName;
    }

    /**
     * @param string[] $classes
     */
    public function compile(array $classes): string
    {
        if ($this->exists()) {
            return $this->cacheFileName;
        }

        $fileContent = [
            '<?php',
            '',
            'return [',
            ...array_map(static fn (string $class) => '  \'' . $class . '\',', $classes),
            '];',
        ];

        $this->createCacheDirectory(dirname($this->cacheFileName));

        $written = file_put_contents($this->cacheFileName, implode("\n", $fileContent));
        if ($written === false) {
            @unlink($this->cacheFileName);
            throw new InvalidArgumentException(sprintf('Error while writing to %s', $this->cacheFileName));
        }

        return $this->cacheFileName;
    }

    public function exists(): bool
    {
        return is_file($this->cacheFileName);
    }

    private function createCacheDirectory(string $directory): void
    {
        if (!is_dir($directory) && !@mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new InvalidArgumentException(sprintf(
                'Cache directory does not exist and cannot be created: %s.',
                $directory
            ));
        }

        if (!is_writable($directory)) {
            throw new InvalidArgumentException(sprintf('Cache directory is not writable: %s.', $directory));
        }
    }
}
