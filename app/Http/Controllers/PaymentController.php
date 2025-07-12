<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Events\OrderPaid;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            $amount = $request->amount; // in INR
            $orderId = $request->order_id;
            $apiKey = env('RAZORPAY_KEY_ID');
            $apiSecret = env('RAZORPAY_KEY_SECRET');
            
            // Validate API credentials
            if (!$apiKey || !$apiSecret) {
                Log::error('Razorpay credentials not found');
                return response()->json(['error' => 'Payment gateway configuration error'], 500);
            }
            
            $razorpayOrderUrl = 'https://api.razorpay.com/v1/orders';
            $orderData = [
                'amount' => (int)($amount * 100), // amount in paise
                'currency' => 'INR',
                'receipt' => 'order_rcpt_' . $orderId,
                'payment_capture' => 1,
            ];
            
            Log::info('Creating Razorpay order', ['order_id' => $orderId, 'amount' => $amount]);
            
            $response = \Http::withBasicAuth($apiKey, $apiSecret)
                ->post($razorpayOrderUrl, $orderData);
                
            if ($response->ok()) {
                $responseData = $response->json();
                Log::info('Razorpay order created successfully', ['razorpay_order_id' => $responseData['id']]);
                return response()->json($responseData);
            } else {
                Log::error('Razorpay order creation failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return response()->json(['error' => 'Unable to create payment order'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception in createOrder', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment gateway error'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        try {
            $razorpayOrderId = $request->razorpay_order_id;
            $razorpayPaymentId = $request->razorpay_payment_id;
            $razorpaySignature = $request->razorpay_signature;
            $orderId = $request->order_id;
            $apiSecret = env('RAZORPAY_KEY_SECRET');
            
            if (!$apiSecret) {
                Log::error('Razorpay secret not found');
                return response()->json(['success' => false, 'error' => 'Payment gateway configuration error'], 500);
            }
            
            $generatedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $apiSecret);
            
            if ($generatedSignature === $razorpaySignature) {
                // Mark order as paid
                Order::where('id', $orderId)->update(['status' => 'paid']);

                // Dispatch OrderPaid event for WhatsApp (queued)
                $order = Order::find($orderId);
                event(new OrderPaid($order));

                Log::info('Payment verified successfully', ['order_id' => $orderId]);
                return response()->json(['success' => true]);
            } else {
                Log::error('Razorpay signature mismatch', [
                    'order_id' => $orderId,
                    'expected' => $generatedSignature,
                    'received' => $razorpaySignature
                ]);
                return response()->json(['success' => false, 'error' => 'Payment verification failed'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception in verifyPayment', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'Payment verification error'], 500);
        }
    }

    public function process(Order $order)
    {
        // Check if order is already paid
        if ($order->status === 'paid') {
            return redirect()->route('thankyou')->with('success', 'Order is already paid!');
        }
        
        return view('payment.process', compact('order'));
    }
} 