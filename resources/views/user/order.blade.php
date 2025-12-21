@extends('user.master')

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> > Orders
</div>
@endsection

@section('content')
<section class="orders-section">
    <h2>My Orders</h2>
    
    @if($orders->count() > 0)
        <div class="table-wrapper">
            <div class="table orders-table">
                <div class="table-header">
                    <div>Order #</div>
                    <div>Date</div>
                    <div>Status</div>
                    <div>Total</div>
                    <div>Actions</div>
                </div>
                @foreach($orders as $order)
                <div class="table-row">
                    <div>
                        <strong>#{{ $order->order_number ?? $order->id }}</strong>
                        <div class="order-items-preview">
                            @if($order->orderItems && $order->orderItems->count() > 0)
                                <small>{{ $order->orderItems->count() }} item(s)</small>
                            @endif
                        </div>
                    </div>
                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                    <div>
                        <span class="badge {{ strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered' ? 'paid' : 'pending' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <strong>₦{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                        @if(in_array($order->status, ['pending', 'processing']))
                            <form method="POST" action="{{ route('user.orders.cancel', $order) }}" style="display: inline; margin-left: 0.5rem;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                            </form>
                        @endif
                        @if(in_array($order->status, ['delivered', 'completed']))
                            <form method="POST" action="{{ route('user.orders.reorder', $order) }}" style="display: inline; margin-left: 0.5rem;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">Reorder</button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>
    @else
        <div class="no-orders">
            <p>You haven't placed any orders yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
        </div>
    @endif
</section>
@endsection