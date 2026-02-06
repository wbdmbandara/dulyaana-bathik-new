<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderUpdateMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $customer;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Order Update - Dulyaana Bathik')
                    ->view('emails.order_update')
                    ->with([
                        'name' => $this->customer->name,
                        'email' => $this->customer->email,
                        'actionUrl' => env('FRONTEND_URL'),
                        'orderDetailsUrl' => env('FRONTEND_URL') . '/order-details?order_id=' . $this->order->id,
                        'order' => $this->order,
                        'orderNumber' => $this->order->id,
                        'year' => date('Y'),
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Update - Dulyaana Bathik',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_update',
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
