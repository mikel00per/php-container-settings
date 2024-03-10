<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Environment;

enum Environment: string
{
    case DEVELOP = 'develop';
    case TESTING = 'testing';
    case PRODUCTION = 'production';

    public function isDevelop(): bool
    {
        return $this === self::DEVELOP;
    }

    public function testing(): bool
    {
        return $this === self::TESTING;
    }

    public function production(): bool
    {
        return $this === self::PRODUCTION;
    }
}
