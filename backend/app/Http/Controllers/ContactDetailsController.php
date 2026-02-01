<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\ContactForm;
use App\Models\FooterContents;
use App\Services\MailConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactDetailsController extends Controller
{
    protected $contactForm;

    public function __construct(ContactForm $contactForm)
    {
        $this->contactForm = $contactForm;
    }

    public function getContactDetails(Request $request)
    {
        $footerContents = FooterContents::first();
        return response()->json([
            'success' => true,
            'contactDetails' => $footerContents,
        ]);
    }

    public function submitContactForm(Request $request){
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactFormEntry = $this->contactForm->create($validatedData);
        
        // Send admin notification
        try {
            MailConfigService::applyMailSettings();
            Mail::to(env('ADMIN_NOTIFICATION_MAIL'))->send(new ContactFormMail($contactFormEntry));
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact form submitted successfully.',
        ]);
    }
}
