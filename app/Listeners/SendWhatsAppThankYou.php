<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsAppThankYou implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPaid $event)
    {
        $order = $event->order;
        if ($order && $order->phone) {
            $apiKey = env('WHATSAPP_API_KEY');
            $sender = env('WHATSAPP_SENDER');
            $api_url = env('WHATSAPP_API_URL', 'https://wa.t7solution.com/send-message');
            $number = $order->phone;
            if (strpos($number, '91') !== 0) {
                $number = '91' . $number;
            }
            $message = "Thank you for your order, {$order->name}! Your payment of ₹{$order->total} was successful. We appreciate your business!";
            $payload = [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $number,
                'message' => $message,
                'footer' => 'Sent By ecommerce',
            ];
            try {
                Http::post($api_url, $payload);
            } catch (\Exception $e) {
                Log::error('WhatsApp message failed', ['error' => $e->getMessage()]);
            }
        }
    }
} 