<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);

        // Get phone number from the notifiable model
        $phone = $notifiable->routeNotificationFor('sms') ?? $notifiable->phone;

        if (!$phone) {
            Log::warning('No phone number for notification', [
                'notifiable_id' => $notifiable->id,
                'notification' => get_class($notification)
            ]);
            return;
        }

        return $this->smsService->send($phone, $message);
    }
}
