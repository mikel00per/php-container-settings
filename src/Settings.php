<?php

declare(strict_types=1);

namespace ContainerSettings;

use Adbar\Dot;

final class Settings extends Dot implements SettingsInterface
{
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }
}
