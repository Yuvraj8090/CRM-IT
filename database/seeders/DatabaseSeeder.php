<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FollowupReasonSeeder::class,
            LeadStatusSeeder::class,
            SettingsTableSeeder::class,
        ]);
        
    }
}
