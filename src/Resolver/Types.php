<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Resolver;

enum Types: string
{
    case INTERFACE = 'interface';
    case ATTRIBUTE = 'attribute';
}
