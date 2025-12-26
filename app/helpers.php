<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('allsettings')) {
    function allsettings($key = null, $default = null)
    {
        // Cache all settings for 1 hour
        $settings = Cache::remember('all_settings', now()->addHours(1), function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        if ($key) {
            return $settings[$key] ?? $default;
        }

        return $settings;
    }
}
