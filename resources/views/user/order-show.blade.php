@extends('user.master')
    <link rel="stylesheet" href="{{ url('css/order-details.css') }}" />


@section('breadcrumb')
<div class="breadcrumb">
    <div class="container">
        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a> > 
            <a href="{{ route('user.orders.index') }}">Orders</a> > 
            <span>Order #{{ $order->order_number ?? $order->id }}</span>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="order-details">
    <div class="order-header">
        <div class="order-title">
            <h1>Order #{{ $order->order_number ?? $order->id }}</h1>
            <span class="status-badge status-{{ strtolower($order->order_status ?? $order->status) }}">
                {{ ucfirst($order->order_status ?? $order->status) }}
            </span>
        </div>
        <div class="order-actions">
            <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to Orders
            </a>
            @if(in_array(strtolower($order->order_status ?? $order->status), ['delivered', 'completed']))
                <form method="POST" action="{{ route('user.orders.reorder', $order) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
                        </svg>
                        Reorder
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="order-content">
        <!-- Order Information Card -->
        <div class="order-info-card">
            <h3>Order Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Order Date:</span>
                    <span class="value">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Payment Method:</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Payment Status:</span>
                    <span class="payment-status status-{{ strtolower($order->payment_status ?? 'pending') }}">
                        {{ ucfirst($order->payment_status ?? 'Pending') }}
                    </span>
                </div>
                @if($order->shipped_at)
                <div class="info-item">
                    <span class="label">Shipped Date:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($order->shipped_at)->format('F d, Y \a\t g:i A') }}</span>
                </div>
                @endif
                @if($order->delivered_at)
                <div class="info-item">
                    <span class="label">Delivered Date:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($order->delivered_at)->format('F d, Y \a\t g:i A') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Shipping Information Card -->
        <div class="shipping-info-card">
            <h3>Shipping Information</h3>
            <div class="shipping-details">
                <div class="address-section">
                    <h4>Shipping Address</h4>
                    <div class="address">
                        <strong>{{ $order->name ?? $order->user->name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        <strong>Phone:</strong> {{ $order->phone ?? $order->user->phone }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="order-items-card">
            <h3>Order Items</h3>
            <div class="items-list">
                @foreach($order->orderItems as $item)
                <div class="order-item">
                    <div class="item-image">
                        @if($item->product && $item->product->image)
                            @php
                                $images = json_decode($item->product->image, true);
                                $firstImage = is_array($images) && !empty($images) ? $images[0] : $item->product->image;
                            @endphp
                            <img src="{{ url('uploads/products/' . $firstImage) }}" alt="{{ $item->product->name }}" />
                        @else
                            <div class="no-image">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" fill="#ccc"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="item-details">
                        <h4>{{ $item->product->name ?? 'Product not found' }}</h4>
                        <p class="item-price">₦{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        @if($item->product && $item->product->description)
                            <p class="item-description">{{ Str::limit($item->product->description, 100) }}</p>
                        @endif
                    </div>
                    <div class="item-total">
                        <span class="total-amount">₦{{ number_format($item->total ?? ($item->price * $item->quantity), 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="order-summary-card">
            <h3>Order Summary</h3>
            <div class="summary-details">
                <div class="summary-row">
                    <span class="label">Subtotal:</span>
                    <span class="value">₦{{ number_format($order->total_amount ?? 0, 2) }}</span>
                </div>
                <!-- @if($order->tax_amount)
                <div class="summary-row">
                    <span class="label">Tax (7.5%):</span>
                    <span class="value">₦{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @endif
                @if($order->shipping_cost)
                <div class="summary-row">
                    <span class="label">Shipping:</span>
                    <span class="value">₦{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                @endif -->
                <div class="summary-row total-row">
                    <span class="label">Total:</span>
                    <span class="value">₦{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if($order->order_notes)
        <!-- Order Notes Card -->
        <div class="order-notes-card">
            <h3>Order Notes</h3>
            <p>{{ $order->order_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection