<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\Cache;

class MailConfigService
{
    public static function applyMailSettings()
    {
        $settings = Cache::remember('email_settings', 3600, function () {
            return EmailSetting::first();
        });

        if ($settings) {
            Config::set('mail.mailers.smtp', [
                'transport' => $settings->mail_mailer ?? 'smtp',
                'host' => $settings->mail_host,
                'port' => $settings->mail_port,
                // 'encryption' => $settings->mail_encryption,
                'username' => $settings->mail_username,
                'password' => $settings->mail_password,
            ]);

            Config::set('mail.from', [
                'address' => $settings->mail_from_address,
                'name' => $settings->mail_from_name,
            ]);
        }
    }
}
