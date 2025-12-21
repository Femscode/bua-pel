<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->latest();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by order number or user name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->paginate(15);
        
        return view('admin.orders', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Orders are typically created by users, not admins
        return redirect()->route('admin.orders.index')
                        ->with('info', 'Orders are created by customers through the frontend.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Orders are typically created by users, not admins
        return redirect()->route('admin.orders.index')
                        ->with('info', 'Orders are created by customers through the frontend.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.order-show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.edit-order', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['status', 'payment_status', 'notes']);
        
        // Set shipped_at timestamp when status changes to shipped
        if ($request->status === 'shipped' && $order->status !== 'shipped') {
            $data['shipped_at'] = now();
        }
        
        // Set delivered_at timestamp when status changes to delivered
        if ($request->status === 'delivered' && $order->status !== 'delivered') {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                            ->with('error', 'Only cancelled orders can be deleted.');
        }
        
        $order->delete();

        return redirect()->route('admin.orders.index')
                        ->with('success', 'Order deleted successfully.');
    }
}
