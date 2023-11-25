<?php

declare(strict_types=1);

namespace Tests\Shared\Infrastructure\Units\DependencyInjection;

use RuntimeException;
use Shared\Infrastructure\DependencyInjection\File;
use Tests\Shared\Infrastructure\Utils\TestCase;

final class FileTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_exception_if_file_not_exist(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not exist');

        File::require('/some');
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_file_not_exist_with_message(): void
    {
        $message = 'Not exist the file';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($message);

        File::require('/some', $message);
    }
}
