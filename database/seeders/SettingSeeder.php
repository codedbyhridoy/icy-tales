<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [];
        $settings[] = [
            'group' => 'system',
            'key' => 'title',
            'value' => 'Dental Match',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $settings[] = [
            'group' => 'system',
            'key' => 'meta_title',
            'value' => 'Dental Match',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $settings[] = [
            'group' => 'system',
            'key' => 'og_title',
            'value' => 'Dental Match',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $settings[] = [
            'group' => 'system',
            'key' => 'logo',
            'value' => '',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $settings[] = [
            'group' => 'system',
            'key' => 'favicon',
            'value' => '',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $settings[] = [
            'group' => 'system',
            'key' => 'meta_description',
            'value' => '',
            'type' => 'setting',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        \DB::table('settings')->insert($settings);
    }
}
