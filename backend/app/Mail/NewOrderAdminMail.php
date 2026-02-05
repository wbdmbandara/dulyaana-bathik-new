<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $items;
    protected $customer;

    /**
     * Create a new message instance.
     */
    public function __construct($order, $items, $customer)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->items = $items;
    }

    public function build()
    {
        return $this->subject('New Order Received - Dulyaana Bathik')
                    ->view('emails.new_order_admin')
                    ->with([
                        'customer' => $this->customer,
                        'orderDetailsUrl' => env('APP_URL') . '/orders?search=' . $this->order->id,
                        'order' => $this->order,
                        'items' => $this->items,
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
            subject: 'New Order Received - Dulyaana Bathik',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_order_admin',
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
