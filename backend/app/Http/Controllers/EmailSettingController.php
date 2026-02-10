<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\EmailSetting;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class EmailSettingController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
        return redirect('/');
        }

        Cache::forget('email_settings');
        $settings = Cache::remember('email_settings', 3600, function () {
            return EmailSetting::first();
        });

        return view('email-settings', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'admin_notification_email' => 'required|email',
        ]);

        $settings = EmailSetting::first();
        if (!$settings) {
            $settings = new EmailSetting();
        }

        $settings->mail_mailer = $request->input('mail_mailer');
        $settings->mail_host = $request->input('mail_host');
        $settings->mail_port = $request->input('mail_port');
        $settings->mail_username = $request->input('mail_username');
        $settings->mail_password = $request->input('mail_password');
        $settings->mail_encryption = $request->input('mail_encryption');
        $settings->mail_from_address = $request->input('mail_from_address');
        $settings->mail_from_name = $request->input('mail_from_name');
        $settings->admin_notification_email = $request->input('admin_notification_email');

        $settings->save();

        Cache::forget('email_settings');
        Cache::remember('email_settings', 3600, function () {
            return EmailSetting::first();
        });

        return redirect('/email-settings')->with('success', 'Email settings updated successfully.');
    }
    
    public function sendTestEmail(Request $request){
        $settings = Cache::get('email_settings');

        if (!$settings) {
            return redirect('/email-settings')->with('error', 'Email settings not configured properly.');
        }

        try {
            MailConfigService::applyMailSettings();
            $testEmail = $request->input('test_email');
            // $testEmail = "dilshanmadusanka20160@gmail.com";
            $testCustomer = (object) [
                'name' => 'Test User',
                'email' => $testEmail,
            ];
            Mail::to($testEmail)->send(new WelcomeMail($testCustomer));
            // dd(config('mail.mailers.smtp'));
            return redirect('/email-settings')->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            return redirect('/email-settings')->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
