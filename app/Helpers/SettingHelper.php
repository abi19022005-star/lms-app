<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Get or set application settings
 *
 * @param string|array|null $key - Setting key or array of settings to set
 * @param mixed $default - Default value if setting not found (only used for GET)
 * @return mixed
 */
function setting($key = null, $default = null)
{
    // If no key provided, return null
    if (is_null($key)) {
        return null;
    }

    // If $key is an array, set multiple settings
    if (is_array($key)) {
        foreach ($key as $k => $v) {
            // Delete OLD first to avoid duplicates
            DB::table('settings')->where('key', $k)->delete();
            // Then insert NEW
            DB::table('settings')->insert([
                'key' => $k,
                'value' => $v,
            ]);
            // Also clear individual setting cache
            Cache::forget('setting.' . $k);
        }
        return null;
    }

    // GET setting: try database directly (bypass cache for reliability)
    $setting = DB::table('settings')
        ->where('key', $key)
        ->first();

    if ($setting) {
        return $setting->value;
    }

    // Return default if setting not found
    return $default;
}

/**
 * Set a single setting value
 *
 * @param string $key
 * @param mixed $value
 * @return mixed
 */
function saveSetting($key, $value)
{
    // Delete OLD first to avoid duplicates
    DB::table('settings')->where('key', $key)->delete();
    // Then insert NEW
    DB::table('settings')->insert([
        'key' => $key,
        'value' => $value,
    ]);
    // Also clear individual setting cache
    Cache::forget('setting.' . $key);
    return $value;
}
