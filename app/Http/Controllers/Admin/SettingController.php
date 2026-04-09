<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Get all settings from database first, then fallback to defaults
        $settings = [
            'app_name' => setting('app_name', config('app.name', 'LMS')),
            'app_description' => setting('app_description', 'Platform e-learning modern'),
            'app_logo' => setting('app_logo', null),
            'contact_email' => setting('contact_email', 'admin@example.com'),
            'contact_phone' => setting('contact_phone', '+62 123 4567 890'),
            'contact_address' => setting('contact_address', 'Jakarta, Indonesia'),
            'social_facebook' => setting('social_facebook', ''),
            'social_twitter' => setting('social_twitter', ''),
            'social_instagram' => setting('social_instagram', ''),
            'social_linkedin' => setting('social_linkedin', ''),
            'mail_driver' => env('MAIL_MAILER', 'smtp'),
            'mail_host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'mail_port' => env('MAIL_PORT', 2525),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'mail_from_name' => env('MAIL_FROM_NAME', setting('app_name', config('app.name', 'LMS'))),
            'payment_method' => setting('payment_method', 'manual'),
            'currency' => setting('currency', 'IDR'),
            'tax_rate' => setting('tax_rate', 11),
            'default_passing_score' => setting('default_passing_score', 70),
            'enable_registration' => setting('enable_registration', true),
            'maintenance_mode' => setting('maintenance_mode', false),
            'maintenance_message' => setting('maintenance_message', 'Situs sedang dalam perawatan. Mohon maaf atas ketidaknyamanannya.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'app_logo' => 'nullable|image|max:2048',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'payment_method' => 'required|in:manual,midtrans,xendit',
            'currency' => 'required|string',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'default_passing_score' => 'required|numeric|min:0|max:100',
            'enable_registration' => 'boolean',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string',
        ]);

        // Handle checkbox fields that might not be sent if unchecked
        $validated['enable_registration'] = $request->has('enable_registration') ? 1 : 0;
        $validated['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('settings', 'public');
            $validated['app_logo'] = $path;

            // Delete old logo if exists
            if (setting('app_logo')) {
                Storage::disk('public')->delete(setting('app_logo'));
            }
        }

        // Save settings to database
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
            
            // Also clear individual setting cache
            Cache::forget('setting.' . $key);
        }

        // Update .env file for mail settings
        $envData = [
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
        ];

        // Only update password if provided
        if (!empty($request->mail_password)) {
            $envData['MAIL_PASSWORD'] = $request->mail_password;
        }

        $this->updateEnvFile($envData);

        // Handle maintenance mode
        if ($request->maintenance_mode) {
            Artisan::call('down', ['--render' => 'maintenance']);
        } else {
            Artisan::call('up');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    private function updateEnvFile($data)
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
// public function index()
    // {
    //     return view('admin.settings.index');
    // }

    // public function update(Request $request)
    // {
    //     $validated = $request->validate([
    //         'app_name' => 'required|string|max:255',
    //         'app_description' => 'nullable|string',
    //         'contact_email' => 'required|email',
    //         'contact_phone' => 'nullable|string',
    //     ]);

    //     foreach ($validated as $key => $value) {
    //         setting([$key => $value]);
    //     }

    //     return redirect()->route('admin.settings.index')
    //         ->with('success', 'Pengaturan berhasil disimpan.');
    // }
}
