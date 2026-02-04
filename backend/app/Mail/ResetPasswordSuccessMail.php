<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordSuccessMail extends Mailable
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

    public function build(){
        return $this->subject('Password Reset Successful')
                    ->view('emails.password_reset_success')
                    ->with([
                        'name' => $this->customer->name,
                        'loginUrl'  => env('FRONTEND_URL') . '/login',
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
            subject: 'Password Reset Successful',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset_success',
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
