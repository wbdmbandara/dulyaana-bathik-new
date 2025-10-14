<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $customer;

    /**
     * Create a new message instance.
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Welcome to Dulyaana Bathik – Discover the Beauty of Batik')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->customer->name,
                        'email' => $this->customer->email,
                        'actionUrl' => env('FRONTEND_URL'),
                        'year' => date('Y'),
                    ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to Dulyaana Bathik – Discover the Beauty of Batik',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
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
