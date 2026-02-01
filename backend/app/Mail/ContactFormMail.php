<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $contactForm;

    /**
     * Create a new message instance.
     */
    public function __construct($contactForm)
    {
        $this->contactForm = $contactForm;
    }

    public function build()
    {
        return $this->subject('New Contact Form Submission')
                    ->view('emails.contact_form')
                    ->with([
                        'name' => $this->contactForm->name,
                        'email' => $this->contactForm->email,
                        'subject' => $this->contactForm->subject,
                        'customerMessage' => $this->contactForm->message,
                        'actionUrl' => env('APP_URL'),
                        'year' => date('Y'),
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Form Submission',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_form',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
