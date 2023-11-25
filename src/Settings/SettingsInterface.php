<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Settings;

interface SettingsInterface
{
	public function __construct(array $data = []);

	public function get(string $key): mixed;
}
