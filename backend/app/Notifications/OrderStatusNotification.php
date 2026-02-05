<?php

namespace App\Notifications;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;


class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $status;

    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        /*
        $message = $this->buildMessage();
        
        $smsService = app(SmsService::class);
        $smsService->send($notifiable->phone, $message);
        */
        return $this->buildMessage();
    }

    protected function buildMessage()
    {
        $OrderID = $this->order->id;
        $contactNo = "0715500661";
        $messages = [
            'newOrder' => "Thank you for your order at Dulyaana Bathik! Your Order ID is #{$OrderID}. We have received your request and are currently reviewing it. Thank you for choosing us!",
            'pmtCompleted' => "Payment Verified! We have successfully confirmed the funds for Order #{$OrderID}. Your order is now being prepared for processing. Thank you for your purchase!",
            'pmtFailed' => "Payment Issue: We could not verify the transfer for Order #{$OrderID}. Please check your bank statement or contact us at {$contactNo} to resolve this quickly.",
            'pmtRefunded' => "Refund Confirmed: The payment for Order #{$OrderID} has been successfully refunded to your account. It may take 1-3 business days to reflect. Dulyaana Bathik.",
            'ordPending' => "Your Dulyaana Bathik Order #{$OrderID} is currently PENDING. We are verifying the details and will update you once it is confirmed. Thank you for your patience!",
            'ordProcessing' => "Your Dulyaana Bathik Order #{$OrderID} is now in processing. Our artisans are carefully preparing your handmade saree for delivery. We appreciate your patience!",
            'ordShipped' => "Your Dulyaana Bathik order is on its way! Order #{$OrderID} was handed to the courier. Tracking No: {$this->order->courier_tracking_no}. Expect delivery within 3-7 business days. Enjoy!",
            'ordCompleted' => "Order Delivered! We hope you love your authentic handmade Dulyaana Bathik saree. Tag us in your photos! For any help, WhatsApp {$contactNo}. Enjoy your saree!",
            'ordCancelled' => "Order #{$OrderID} has been cancelled. If this was a mistake or you require assistance regarding your order, please contact Dulyaana Bathik at {$contactNo}.",
            // 'ordDelivered' => "Your order #{$OrderID} has been delivered. Thank you for shopping with us!",
        ];

        return $messages[$this->status] ?? "Your order #{$this->order->id} status: {$this->status}";
    }
}
