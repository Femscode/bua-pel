<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function processPayment($orderId)
    {
        // $order = Order::findOrFail($orderId);
        $order = Order::where('order_number',$orderId)->first();
    //   dd($order->session_id ,Session::getId());
        // Ensure user can only access their own orders
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        } elseif (!Auth::check() && $order->session_id !== Session::getId()) {
            abort(403);
        }

        // Prepare Flutterwave payment data
        $paymentData = [
            'tx_ref' => $order->order_number,
            'amount' => $order->total_amount,
            'currency' => 'NGN',
            'redirect_url' => route('payment.callback'),
            'payment_options' => 'card,banktransfer,ussd',
            'customer' => [
                'email' => $order->user->email ?? $order->email,
                'phonenumber' => $order->user->phone ?? $order->phone,
                'name' => $order->user->name ?? $order->name
            ],
            'customizations' => [
                'title' => 'Oplug Energies Payment',
                'description' => 'Payment for Order #' . $order->order_number,
                'logo' => url('homepage/images/logo.png')
            ],
            'meta' => [
                'order_id' => $order->id
            ]
        ];

        return view('frontend.payment', compact('order', 'paymentData'));
    }

    public function paymentCallback(Request $request)
    {
        $status = $request->status;
       
        $txRef = $request->tx_ref;
        $transactionId = $request->transaction_id;

        // Find order by transaction reference
        $order = Order::where('order_number', $txRef)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        if ($status === 'completed') {
            // Verify payment with Flutterwave
            $verified = $this->verifyPayment($transactionId);
           
            if ($verified) {
                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'confirmed',
                    'transaction_id' => $transactionId
                ]);

                // Clear cart
                $this->clearCart($order);

                return redirect()->route('order.success', $order->order_number)
                    ->with('success', 'Payment successful! Your order has been confirmed.');
            } else {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled'
                ]);

                return redirect()->route('payment.failed', $order->order_number)
                    ->with('error', 'Payment verification failed.');
            }
        } else {
            // Payment failed or cancelled
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled'
            ]);

            return redirect()->route('payment.failed', $order->order_number)
                ->with('error', 'Payment was not successful.');
        }
    }

    private function verifyPayment($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('FLUTTERWAVE_SECRET_KEY'),
                'Content-Type' => 'application/json'
            ])->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

            $data = $response->json();

            if ($data['status'] === 'success' && $data['data']['status'] === 'successful') {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return false;
        }
    }

    private function clearCart($order)
    {
        if ($order->user_id) {
            // Clear cart for authenticated user
            Cart::where('user_id', $order->user_id)->delete();
        } else {
            // Clear cart for guest user
            Cart::where('session_id', $order->session_id)->delete();
        }
    }

    public function paymentFailed($orderId)
    {
        $order = Order::where('order_number',$orderId)->first();
        
        // Ensure user can only access their own orders
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        } elseif (!Auth::check() && $order->session_id !== Session::getId()) {
            abort(403);
        }

        return view('frontend.payment-failed', compact('order'));
    }
}