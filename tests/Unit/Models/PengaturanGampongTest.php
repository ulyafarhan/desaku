<?php

namespace Tests\Unit\Models;

use App\Models\PengaturanGampong;
use Tests\TestCase;

class PengaturanGampongTest extends TestCase
{
    public function test_can_get_setting_value()
    {
        PengaturanGampong::create([
            'kunci' => 'test_key',
            'nilai' => 'test_value',
            'tipe_data' => 'string',
        ]);

        $value = PengaturanGampong::get('test_key');

        $this->assertEquals('test_value', $value);
    }

    public function test_returns_default_if_key_not_found()
    {
        $value = PengaturanGampong::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    public function test_can_set_setting_value()
    {
        PengaturanGampong::set('new_key', 'new_value');

        $this->assertDatabaseHas('pengaturan_gampong', [
            'kunci' => 'new_key',
            'nilai' => 'new_value',
        ]);
    }

    public function test_handles_integer_type()
    {
        PengaturanGampong::set('int_key', 123, 'integer');

        $value = PengaturanGampong::get('int_key');

        $this->assertIsInt($value);
        $this->assertEquals(123, $value);
    }

    public function test_handles_boolean_type()
    {
        PengaturanGampong::set('bool_key', true, 'boolean');

        $value = PengaturanGampong::get('bool_key');

        $this->assertIsBool($value);
        $this->assertTrue($value);
    }

    public function test_handles_json_type()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        PengaturanGampong::set('json_key', $data, 'json');

        $value = PengaturanGampong::get('json_key');

        $this->assertIsArray($value);
        $this->assertEquals($data, $value);
    }

    /**
     * Memastikan metode set dapat menangani nilai null dengan mengubahnya menjadi string kosong.
     */
    public function test_set_menangani_nilai_null_dengan_mengubahnya_ke_string_kosong()
    {
        PengaturanGampong::set('null_key', null);

        $this->assertDatabaseHas('pengaturan_gampong', [
            'kunci' => 'null_key',
            'nilai' => '',
        ]);

        $value = PengaturanGampong::get('null_key');
        $this->assertEquals('', $value);
    }
}
