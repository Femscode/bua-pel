@extends('frontend.master')

@section('title', 'Order Success - Oplug Energies')

@section('content')
<div class="solar-breadcrumb">
    <div class="solar-breadcrumb-container">
        <div class="solar-breadcrumb-content">
            <a href="{{ route('home') }}" class="solar-breadcrumb-link">Home</a>
            <span class="solar-breadcrumb-separator">></span>
            <span class="solar-breadcrumb-current">Order Success</span>
        </div>
    </div>
</div>

<div class="os-order-success">
    <div class="os-order-success-container">
        <div class="os-order-success-content">
            <div class="success-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="#27ae60"/>
                    <path d="M8 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <h1>Order Placed Successfully!</h1>
            <p class="success-message">
                @if($order->payment_method === 'cash_on_delivery')
                    Thank you for your order! You will pay upon delivery.
                @else
                    Thank you for your payment! Your order has been confirmed.
                @endif
            </p>

            <div class="order-details">
                <h3>Order Details</h3>
                <div class="order-info">
                    <div class="info-item">
                        <span class="label">Order Number:</span>
                        <span class="value">{{ $order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Order Date:</span>
                        <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Payment Method:</span>
                        <span class="value">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Payment Status:</span>
                        <span class="value status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total Amount:</span>
                        <span class="value total">₦{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="order-items">
                <h3>Items Ordered</h3>
                <div class="items-list">
                    @foreach($order->orderItems as $item)
                    <div class="order-item">
                        @php
                            $images = $item->product->image ? json_decode($item->product->image, true) : [];
                            $firstImage = !empty($images) ? $images[0] : null;
                            $imageUrl = $firstImage ? 'https://best4uarena.com/best4u_files/public/uploads/products/' . $firstImage : url('homepage/images/home/solar1.png');
                        @endphp
                        <div class="item-image" style="background-image: url('{{ $imageUrl }}');"></div>
                        <div class="item-details">
                            <h4>{{ $item->product->name }}</h4>
                            <p>Quantity: {{ $item->quantity }}</p>
                            <p class="item-price">₦{{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="order-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
                @auth
                <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">View My Orders</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
.os-order-success {
    padding: 40px 20px;
    background-color: #f8f9fa;
    min-height: 70vh;
}

.os-order-success-container {
    max-width: 800px;
    margin: 0 auto;
}

.os-order-success-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.success-icon {
    margin-bottom: 20px;
}

.os-order-success-content h1 {
    color: #27ae60;
    margin-bottom: 15px;
    font-size: 32px;
}

.success-message {
    color: #7f8c8d;
    font-size: 18px;
    margin-bottom: 40px;
}

.order-details, .order-items {
    text-align: left;
    margin-bottom: 30px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
}

.order-details h3, .order-items h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 8px 0;
}

.info-item .label {
    font-weight: 600;
    color: #2c3e50;
}

.info-item .value {
    color: #7f8c8d;
}

.info-item .total {
    font-weight: bold;
    font-size: 18px;
    color: #2c3e50;
}

.status-paid {
    color: #27ae60;
    font-weight: bold;
}

.status-pending {
    color: #f39c12;
    font-weight: bold;
}

.order-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    background-size: cover;
    background-position: center;
    border-radius: 8px;
    margin-right: 15px;
    flex-shrink: 0;
}

.item-details h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 16px;
}

.item-details p {
    margin: 2px 0;
    color: #7f8c8d;
    font-size: 14px;
}

.item-price {
    font-weight: bold;
    color: #2c3e50 !important;
}

.order-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .os-order-success-content {
        padding: 20px;
    }
    
    .os-order-success-content h1 {
        font-size: 24px;
    }
    
    .order-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
    }
}
</style>
@endsection