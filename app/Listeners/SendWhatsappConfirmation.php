<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SendWhatsappConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderPlaced $event)
    {
        $order = $event->order;
        $user = $order->user;
        $phone = $user && $user->phone ? $user->phone : null;
        if (!$phone && isset($order->customer_phone)) {
            $phone = $order->customer_phone;
        }
        if (!$phone) {
            // No phone number, skip sending
            return;
        }
        // Ensure phone starts with '91'
        if (!str_starts_with($phone, '91')) {
            $phone = '91' . ltrim($phone, '0');
        }
        $apiKey = env('WHATSAPP_API_KEY');
        $sender = env('WHATSAPP_SENDER');
        $api_url = env('WHATSAPP_API_URL', 'https://wa.t7solution.com/send-message');
        $message = 'Thank you for your order #' . $order->id . '! Total: ₹' . $order->total;
        $payload = [
            'api_key' => $apiKey,
            'sender' => $sender,
            'number' => $phone,
            'message' => $message,
            'footer' => 'Sent By madCare',
        ];
        $status = 'sent';
        try {
            $response = Http::post($api_url, $payload);
            if (!$response->ok() || !str_contains($response->body(), 'success')) {
                $status = 'failed';
            }
        } catch (\Exception $e) {
            $status = 'failed';
        }
        DB::table('whatsapp_queue')->insert([
            'order_id' => $order->id,
            'message' => $message,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 