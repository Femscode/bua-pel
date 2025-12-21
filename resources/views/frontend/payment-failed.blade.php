@extends('frontend.master')

@section('title', 'Payment Failed - Best4UArena')

@section('content')
<div class="pf-breadcrumb">
    <div class="pf-breadcrumb-container">
        <div class="pf-breadcrumb-content">
            <a href="{{ route('home') }}" class="pf-breadcrumb-link">Home</a>
            <span class="pf-breadcrumb-separator">></span>
            <span class="pf-breadcrumb-current">Payment Failed</span>
        </div>
    </div>
</div>

<div class="pf-payment-failed">
    <div class="pf-payment-failed-container">
        <div class="pf-payment-failed-content">
            <div class="failed-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="#e74c3c"/>
                    <path d="M15 9l-6 6M9 9l6 6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <h1>Payment Failed</h1>
            <p class="failed-message">
                Unfortunately, your payment could not be processed. Please try again or contact support if the problem persists.
            </p>

            @if(isset($order))
            <div class="order-details">
                <h3>Order Information</h3>
                <div class="order-info">
                    <div class="info-item">
                        <span class="label">Order Number:</span>
                        <span class="value">{{ $order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Total Amount:</span>
                        <span class="value total">₦{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Payment Status:</span>
                        <span class="value status-failed">Failed</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="failure-reasons">
                <h3>Common Reasons for Payment Failure</h3>
                <ul>
                    <li>Insufficient funds in your account</li>
                    <li>Incorrect card details entered</li>
                    <li>Network connectivity issues</li>
                    <li>Card expired or blocked</li>
                    <li>Transaction limit exceeded</li>
                </ul>
            </div>

            <div class="payment-actions">
               
                <a href="{{ route('cart') }}" class="btn btn-secondary">Return to Cart</a>
                <a href="{{ route('home') }}" class="btn btn-outline">Continue Shopping</a>
            </div>

            <div class="support-info">
                <p>Need help? Contact our support team:</p>
                <div class="support-contacts">
                    <div class="contact-item">
                        <strong>Email:</strong> support@best4uarena.com
                    </div>
                    <div class="contact-item">
                        <strong>Phone:</strong> +234 (0) 907 635 1112
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pf-payment-failed {
    padding: 40px 20px;
    background-color: #f8f9fa;
    min-height: 70vh;
}

.pf-payment-failed-container {
    max-width: 800px;
    margin: 0 auto;
}

.pf-payment-failed-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.failed-icon {
    margin-bottom: 20px;
}

.pf-payment-failed-content h1 {
    color: #e74c3c;
    margin-bottom: 15px;
    font-size: 32px;
}

.failed-message {
    color: #7f8c8d;
    font-size: 18px;
    margin-bottom: 40px;
    line-height: 1.6;
}

.order-details, .failure-reasons {
    text-align: left;
    margin-bottom: 30px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
}

.order-details h3, .failure-reasons h3 {
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

.status-failed {
    color: #e74c3c;
    font-weight: bold;
}

.failure-reasons ul {
    list-style: none;
    padding: 0;
}

.failure-reasons li {
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
    color: #7f8c8d;
    position: relative;
    padding-left: 20px;
}

.failure-reasons li:before {
    content: "•";
    color: #e74c3c;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.failure-reasons li:last-child {
    border-bottom: none;
}

.payment-actions {
    margin: 30px 0;
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
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: #2c3e50;
    border: 2px solid #2c3e50;
}

.btn-outline:hover {
    background: #2c3e50;
    color: white;
    transform: translateY(-2px);
}

.support-info {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e9ecef;
    text-align: center;
}

.support-info p {
    color: #7f8c8d;
    margin-bottom: 15px;
    font-size: 16px;
}

.support-contacts {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

.contact-item {
    color: #2c3e50;
    font-size: 14px;
}

.contact-item strong {
    color: #e74c3c;
}

@media (max-width: 768px) {
    .solar-payment-failed-content {
        padding: 20px;
    }
    
    .solar-payment-failed-content h1 {
        font-size: 24px;
    }
    
    .payment-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 250px;
    }
    
    .support-contacts {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection