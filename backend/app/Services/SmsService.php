<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiUrl;
    // protected $apiKey;
    protected $senderId;
    protected $countryCode;
    
    public function __construct()
    {
        $this->apiUrl = config('services.sms.api_url');
        // $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id');
        $this->countryCode = '94'; // Default country code
    }

    public function send($phoneNumber, $message)
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        try {
            if(env('SMS_ENABLED') !== true) {
                // Mock response for testing
                Log::info('SMS sending is disabled. Mock send.', [
                    'phone' => $formattedPhone,
                    'message' => $message
                ]);
                return true;
            } else {
                // Actual SMS sending
                $response = Http::get($this->apiUrl . '?mask=' . urlencode($this->senderId) . '&text=' . urlencode($message) . '&number=' . $formattedPhone);

                if ($response->successful()) {
                    Log::info('SMS sent successfully', [
                        'phone' => $formattedPhone,
                        'response' => $response->json()
                    ]);
                    return true;
                }

                Log::error('SMS sending failed', [
                    'phone' => $formattedPhone,
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS exception', [
                'phone' => $formattedPhone,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters (spaces, dashes, etc.)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading zero if present
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        
        // Add country code if not already present
        if (substr($phone, 0, strlen($this->countryCode)) !== $this->countryCode) {
            $phone = $this->countryCode . $phone;
        }
        
        return $phone;
    }
}
