<?php

declare(strict_types=1);

namespace ContainerSettings;

use DI\Container;

interface ContainerBuilderInterface
{
    public static function buildContainer(string $pathSettings): Container;
}
