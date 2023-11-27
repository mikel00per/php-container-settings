<?php

declare(strict_types=1);

namespace Shared\Infrastructure\DependencyInjection;

interface CompilerPass
{
    public function process(ContainerBuilder $containerBuilder): void;
}
