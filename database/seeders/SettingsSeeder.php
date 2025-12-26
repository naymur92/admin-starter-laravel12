<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => config('app.name'),
                'type' => 'string',
                'group' => 'site',
                'description' => 'Application name displayed throughout the site',
            ],
            [
                'key' => 'timezone',
                'value' => config('app.timezone'),
                'type' => 'string',
                'group' => 'site',
                'description' => 'Application timezone for date/time display',
            ],
            [
                'key' => 'app_description',
                'value' => 'A secure application management system',
                'type' => 'string',
                'group' => 'site',
                'description' => 'Brief description of the application',
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@example.com',
                'type' => 'string',
                'group' => 'site',
                'description' => 'Primary contact email address',
            ],
            [
                'key' => 'contact_phone',
                'value' => '',
                'type' => 'string',
                'group' => 'site',
                'description' => 'Primary contact phone number',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Enable maintenance mode to prevent user access',
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10240',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Maximum file upload size in KB',
            ],
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'User session lifetime in minutes',
            ],
            [
                'key' => 'items_per_page',
                'value' => '10',
                'type' => 'integer',
                'group' => 'general',
                'description' => 'Number of items to display per page',
            ],
            [
                'key' => 'enable_registration',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'auth',
                'description' => 'Allow new user registrations',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
