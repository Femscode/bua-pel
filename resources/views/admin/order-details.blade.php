@extends('admin.master')

@section('breadcrumb')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a> > 
    <a href="{{ route('admin.orders') }}">Orders</a> > 
    Order #{{ $order->order_number ?? $order->id }}
</div>
@endsection

@section('content')
<div class="dashboard">
    <header class="dashboard-header">
        <h1>Order Details - #{{ $order->order_number ?? $order->id }}</h1>
        <div class="order-actions">
            <a href="{{ route('admin.orders') }}" class="btn btn-secondary">Back to Orders</a>
            <button onclick="window.print()" class="btn btn-primary">Print Order</button>
        </div>
    </header>

    <div class="order-details-container">
        <!-- Order Status Card -->
        <div class="order-status-card">
            <div class="status-header">
                <h3>Order Status</h3>
                <span class="badge {{ strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered' ? 'paid' : 'pending' }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="status-details">
                <div class="status-item">
                    <label>Order Date:</label>
                    <span>{{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="status-item">
                    <label>Payment Status:</label>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'paid' : 'pending' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
                <div class="status-item">
                    <label>Payment Method:</label>
                    <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                </div>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="customer-info-card">
            <h3>Customer Information</h3>
            <div class="customer-details">
                <div class="customer-item">
                    <label>Name:</label>
                    <span>{{ $order->name }}</span>
                </div>
                <div class="customer-item">
                    <label>Email:</label>
                    <span>{{ $order->email }}</span>
                </div>
                <div class="customer-item">
                    <label>Phone:</label>
                    <span>{{ $order->phone }}</span>
                </div>
                <div class="customer-item">
                    <label>Shipping Address:</label>
                    <span>{{ $order->shipping_address }}</span>
                </div>
                @if($order->user)
                <div class="customer-item">
                    <label>Customer ID:</label>
                    <span>#{{ $order->user->id }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="order-items-card">
            <h3>Order Items</h3>
            <div class="items-table">
                <div class="table-header">
                    <span>Product</span>
                    <span>Price</span>
                    <span>Quantity</span>
                    <span>Total</span>
                </div>
                @foreach($order->orderItems as $item)
                <div class="table-row">
                    <div class="product-info">
                        @if($item->product && $item->product->image)
                            @php
                                $images = is_string($item->product->image) ? json_decode($item->product->image, true) : $item->product->image;
                                $firstImage = is_array($images) ? $images[0] : $images;
                            @endphp
                            <img src="{{ 'https://best4uarena.com/best4u_files/public/uploads/products/' . $firstImage }}" alt="{{ $item->product->name }}" class="product-image">
                        @else
                            <div class="product-placeholder">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                            </div>
                        @endif
                        <div class="product-details">
                            <h4>{{ $item->product->name ?? 'Product Not Found' }}</h4>
                            @if($item->product && $item->product->sku)
                                <small>SKU: {{ $item->product->sku }}</small>
                            @endif
                        </div>
                    </div>
                    <span>₦{{ number_format($item->price, 2) }}</span>
                    <span>{{ $item->quantity }}</span>
                    <span>₦{{ number_format($item->total, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="order-summary-card">
            <h3>Order Summary</h3>
            <div class="summary-details">
                <div class="summary-item">
                    <label>Subtotal:</label>
                    <span>₦{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax_amount > 0)
                <div class="summary-item">
                    <label>Tax:</label>
                    <span>₦{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                @endif
                @if($order->shipping_cost > 0)
                <div class="summary-item">
                    <label>Shipping:</label>
                    <span>₦{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                @endif
                <div class="summary-item total">
                    <label>Total:</label>
                    <span>₦{{ number_format($order->total_amount, 2) }}</span>
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

<style>
.order-details-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.order-status-card,
.customer-info-card,
.order-items-card,
.order-summary-card,
.order-notes-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-items-card,
.order-notes-card {
    grid-column: 1 / -1;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.status-details,
.customer-details,
.summary-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.status-item,
.customer-item,
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-item.total {
    font-weight: bold;
    font-size: 1.1em;
    border-top: 2px solid #003177;
    margin-top: 10px;
    padding-top: 15px;
}

.items-table .table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    font-weight: bold;
    border-radius: 8px 8px 0 0;
}

.items-table .table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #eee;
    align-items: center;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}

.product-placeholder {
    width: 40px;
    height: 40px;
    background: #f8f9fa;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.product-details h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
}

.product-details small {
    color: #6c757d;
    font-size: 12px;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .order-details-container {
        grid-template-columns: 1fr;
    }
    
    .items-table .table-header,
    .items-table .table-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .product-info {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .order-actions {
        width: 100%;
        justify-content: flex-start;
    }
}

@media print {
    .order-actions,
    .breadcrumb {
        display: none;
    }
    
    .order-details-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>
@endsection