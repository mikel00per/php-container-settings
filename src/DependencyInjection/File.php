<?php

declare(strict_types=1);

namespace Shared\Infrastructure\DependencyInjection;

use Iterator;
use RuntimeException;
use Symfony\Component\Finder\Finder;

final class File
{
    public static function require(string $path, string $message = null): mixed
    {
        if (!file_exists($path)) {
            throw new RuntimeException($message ?: 'File not exist');
        }

        return require $path;
    }

    public static function findPhpFilesIn(string $directory): Iterator
    {
        $finder = new Finder();

        return $finder->files()->in($directory)->name('*.php')->getIterator();
    }
}
