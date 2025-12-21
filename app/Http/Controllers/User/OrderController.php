<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->orders()
                     ->with(['orderItems.product'])
                     ->latest()
                     ->paginate(10);
        
        return view('user.order', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }
        
        $order->load(['orderItems.product']);
        return view('user.order-show', compact('order'));
    }

    /**
     * Cancel an order (only if pending or processing).
     */
    public function cancel(Order $order)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }
        
        // Only allow cancellation of pending or processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('user.orders.show', $order)
                            ->with('error', 'This order cannot be cancelled.');
        }
        
        $order->update(['status' => 'cancelled']);
        
        return redirect()->route('user.orders.show', $order)
                        ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Reorder - create a new order with same items.
     */
    public function reorder(Order $order)
    {
        // Ensure user can only reorder their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }
        
        // Generate new order number
        $newOrderNumber = 'ORD-' . strtoupper(uniqid());
        
        // Create new order
        $newOrder = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $newOrderNumber,
            'status' => 'pending',
            'total_amount' => $order->total_amount,
            'tax_amount' => $order->tax_amount,
            'shipping_address' => $order->shipping_address,
            'billing_address' => $order->billing_address,
            'payment_method' => $order->payment_method,
            'payment_status' => 'pending',
        ]);
        
        // Copy order items
        foreach ($order->orderItems as $item) {
            $newOrder->orderItems()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }
        
        return redirect()->route('user.orders.show', $newOrder)
                        ->with('success', 'Order recreated successfully.');
    }
}
