<?php

declare(strict_types=1);

namespace Mikelooper\ContainerSettings;

use DI\Container;
use DI\ContainerBuilder;

interface ContainerBuilderInterface
{
    public static function buildContainer(string $pathSettings): Container;
}
