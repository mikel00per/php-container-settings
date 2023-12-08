<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Resolver;

use ReflectionClass;
use ReflectionException;
use Shared\Infrastructure\DependencyInjection\File;

final readonly class Resolver
{
    public function __construct() {}

    /**
     * @param class-string $toResolve Attribute, interface...
     *
     * @throws ReflectionException
     */
    public function resolve(
        string $toResolve,
        Type $type,
        string $directory = '',
        string $rootNamespace = null,
        string $rootPath = null,
        ?string $pathCacheFile = null,
    ): array {
        if ($pathCacheFile) {
            $cache = new ResolverCache($toResolve, $pathCacheFile);
            if (!$cache->exists()) {
                $classes = $this->searchForClasses($toResolve, $type, $directory, $rootNamespace, $rootPath);
                $compile = $cache->compile($classes);
                return File::require($compile);
            }

            return File::require($cache->get());
        }

        return $this->searchForClasses($toResolve, $type, $directory, $rootNamespace, $rootPath);
    }

    /**
     * @param class-string $toResolve
     *
     * @throws ReflectionException
     */
    private function searchForClasses(
        string $toResolve,
        Type $type,
        string $directory,
        ?string $rootNamespace = '\\',
        ?string $rootPath = null,
    ): array {
        $searchInDirectories = ($rootPath ?: '.') . '/' . $directory;

        $classes = [];
        foreach (File::findPhpFilesIn($searchInDirectories) as $file) {
            $str = $rootPath . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
            /** @var string $replace */
            $replace = str_replace($str, '', $file->getRealPath());
            $class = trim($replace);
            /** @var class-string $class */
            $class = $rootNamespace . str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $class);

            $reflectionClass = new ReflectionClass($class);

            if ($reflectionClass->isAbstract()) {
                continue;
            }

            $interfaceNames = $reflectionClass->getInterfaceNames();
            $notImplementInterface = $type === Type::INTERFACE && !in_array($toResolve, $interfaceNames, true);

            if ($notImplementInterface) {
                continue;
            }

            $hasAttribute = $type === Type::ATTRIBUTE && !$reflectionClass->getAttributes($toResolve);

            if ($hasAttribute) {
                continue;
            }

            $classes[] = $class;
        }

        return $classes;
    }
}
