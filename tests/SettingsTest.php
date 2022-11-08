<?php

namespace Tests\ContainerSettings;

use ContainerSettings\Settings;
use PHPUnit\Framework\TestCase;
use stdClass;

class SettingsTest extends TestCase
{
    private Settings $settings;
    private array $data;

    protected function setUp(): void
    {
        $this->data = [
            null,
            [],
            [1],
            [1, '1'],
            1 => 1,
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

        $this->settings = new Settings($this->data);
    }

    public function testCreateSettings(): void
    {
        $this->assertInstanceOf(Settings::class, $this->settings);
    }

    public function testGetWithoutParams(): void
    {
        $actual = $this->settings->get();
        $expected = $this->data;

        $this->assertEquals($actual, $expected);
    }

    public function testGetLevelOne(): void
    {
        $this->assertNull($this->settings->get(0));

        $this->assertIsInt($this->settings->get(1));

        $this->assertIsArray($this->settings->get(2));
        $this->assertCount(1, $this->settings->get(2));

        $this->assertIsArray($this->settings->get(3));
        $this->assertCount(2, $this->settings->get(3));

        $this->assertNull($this->settings->get(4));

        $this->assertNull($this->settings->get('setting1'));

        $this->assertIsInt($this->settings->get('setting2'));

        $this->assertIsFloat($this->settings->get('setting3'));

        $this->assertIsString($this->settings->get('setting4'));

        $this->assertIsArray($this->settings->get('setting5'));
        $this->assertCount(2, $this->settings->get('setting5'));

        $this->assertInstanceOf(stdClass::class, $this->settings->get('setting6'));
    }

    public function testGetLevelTwo(): void
    {
        $this->assertIsString($this->settings->get('setting5.0'));

        $this->assertIsArray($this->settings->get('setting5.1'));
        $this->assertCount(1, $this->settings->get('setting5.1'));
    }

    public function testGetLevelThree(): void
    {
        $this->assertIsString($this->settings->get('setting5.1.0'));
    }
}
