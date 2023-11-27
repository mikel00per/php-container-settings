<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Environment;

enum Environment: string
{
    case DEVELOP = 'develop';
    case TESTING = 'testing';
    case PRODUCTION = 'production';
}
