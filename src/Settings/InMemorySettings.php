<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Settings;

use RuntimeException;

final readonly class InMemorySettings implements SettingsInterface
{
    public function __construct(private array $data = []) {}

    public function get(string $key = null): mixed
    {
        if (!$key) {
            return $this->data;
        }

        $settings = $this->data;
        $parents = explode('.', $key);

        foreach ($parents as $parent) {
            $exist = is_array($settings) && (isset($settings[$parent]) || array_key_exists($parent, $settings));

            if (!$exist) {
                $message = sprintf('Trying to fetch invalid setting "%s"', implode('.', $parents));
                throw new RuntimeException($message);
            }

            $settings = $settings[$parent];
        }

        return $settings;
    }
}
