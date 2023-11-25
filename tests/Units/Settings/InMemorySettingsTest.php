<?php

namespace Tests\Shared\Infrastructure\Units\Settings;

use RuntimeException;
use Shared\Infrastructure\Settings;
use Shared\Infrastructure\Settings\InMemorySettings;
use stdClass;
use Tests\Shared\Infrastructure\Utils\TestCase;

final class InMemorySettingsTest extends TestCase
{
    private InMemorySettings $settings;
    private array $data;

    public function setUp(): void
    {
        $this->data = [
            'setting1' => null,
            'setting2' => 1,
            'setting3' => 1.0,
            'setting4' => 'value1',
            'setting5' => [
                '0' => 'value2',
                '1' => [
                    '0' => 'value3'
                ]
            ],
            'setting6' => new stdClass()
        ];

        $this->settings = new InMemorySettings($this->data);
    }

    /** @test */
    public function create_settings(): void
    {
        $this->assertInstanceOf(InMemorySettings::class, $this->settings);
    }

    /** @test */
    public function get_without_params(): void
    {
        $actual = $this->settings->get();
        $expected = $this->data;

        $this->assertEquals($actual, $expected);
    }

    /** @test */
    public function it_expect_get_the_correct_types_in_first_level(): void
    {
        $this->assertNull($this->settings->get('setting1'));

        $this->assertIsInt($this->settings->get('setting2'));

        $this->assertIsFloat($this->settings->get('setting3'));

        $this->assertIsString($this->settings->get('setting4'));

        $this->assertIsArray($this->settings->get('setting5'));
        $this->assertCount(2, $this->settings->get('setting5'));

        $this->assertInstanceOf(stdClass::class, $this->settings->get('setting6'));
    }

    /** @test */
    public function it_expect_get_a_string_and_array_in_second_level(): void
    {
        $this->assertIsString($this->settings->get('setting5.0'));

        $this->assertIsArray($this->settings->get('setting5.1'));
        $this->assertCount(1, $this->settings->get('setting5.1'));
    }

    /** @test */
    public function it_expect_get_a_string_in_third_level(): void
    {
        $this->assertIsString($this->settings->get('setting5.1.0'));
    }

    /** @test */
    public function it_expect_an_error_on_try_get_a_non_exist_value(): void
    {
        $this->expectException(RuntimeException::class);
        $this->settings->get('not.exist.value');
    }
}
