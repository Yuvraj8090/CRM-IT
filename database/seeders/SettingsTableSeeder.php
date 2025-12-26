<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site.title',
                'display_name' => 'Site Title',
                'value' => 'My Awesome Site',
                'details' => null,
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ],
            [
                'key' => 'site.description',
                'display_name' => 'Site Description',
                'value' => 'This is the default site description.',
                'details' => null,
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ],
            [
                'key' => 'site.logo',
                'display_name' => 'Site Logo',
                'value' => null, // default empty, user uploads later
                'details' => null,
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ],
            [
                'key' => 'site.favicon',
                'display_name' => 'Favicon',
                'value' => null, // default empty
                'details' => null,
                'type' => 'image',
                'order' => 4,
                'group' => 'Site',
            ],
            [
                'key' => 'admin.title',
                'display_name' => 'Admin Title',
                'value' => 'Admin Panel',
                'details' => null,
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
