@extends('frontend.master')

@section('title', 'Payment - Oplug Energies')

@section('content')
<div class="solar-breadcrumb">
    <div class="solar-breadcrumb-container">
        <div class="solar-breadcrumb-content">
            <a href="{{ route('home') }}" class="solar-breadcrumb-link">Home</a>
            <span class="solar-breadcrumb-separator">/</span>
            <a href="{{ route('cart') }}" class="solar-breadcrumb-link">Cart</a>
            <span class="solar-breadcrumb-separator">/</span>
            <a href="{{ route('checkout') }}" class="solar-breadcrumb-link">Checkout</a>
            <span class="solar-breadcrumb-separator">/</span>
            <span class="solar-breadcrumb-current">Payment</span>
        </div>
    </div>
</div>

<div class="solar-payment">
    <div class="solar-payment-container">
        <div class="solar-payment-content">
            <div class="solar-payment-header">
                <h1>Complete Your Payment</h1>
                <p>Order #{{ $order->order_number }}</p>
            </div>

            <div class="solar-payment-summary">
                <h3>Order Summary</h3>
                <div class="payment-summary-item">
                    <span>Subtotal:</span>
                    <span>₦{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="payment-summary-item">
                    <span>Tax (7.5%):</span>
                    <span>₦{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="payment-summary-item">
                    <span>Shipping:</span>
                    <span>₦{{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="payment-summary-total">
                    <span>Total:</span>
                    <span>₦{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="solar-payment-method">
                <h3>Payment Method</h3>
                <p>Secure payment powered by Flutterwave</p>
                <button id="pay-now-btn" class="solar-payment-btn">
                    Pay ₦{{ number_format($order->total_amount, 2) }} Now
                </button>
            </div>

            <div class="solar-payment-security">
                <p>🔒 Your payment information is secure and encrypted</p>
            </div>
        </div>
    </div>
</div>

<style>
.solar-payment {
    padding: 40px 20px;
    background-color: #f8f9fa;
    min-height: 60vh;
}

.solar-payment-container {
    max-width: 600px;
    margin: 0 auto;
}

.solar-payment-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.solar-payment-header {
    text-align: center;
    margin-bottom: 30px;
}

.solar-payment-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 28px;
}

.solar-payment-header p {
    color: #7f8c8d;
    font-size: 16px;
}

.solar-payment-summary {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.solar-payment-summary h3 {
    margin-bottom: 15px;
    color: #2c3e50;
}

.payment-summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 5px 0;
}

.payment-summary-total {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 18px;
    border-top: 2px solid #e9ecef;
    padding-top: 15px;
    margin-top: 15px;
    color: #2c3e50;
}

.solar-payment-method {
    text-align: center;
    margin-bottom: 30px;
}

.solar-payment-method h3 {
    margin-bottom: 10px;
    color: #2c3e50;
}

.solar-payment-method p {
    color: #7f8c8d;
    margin-bottom: 20px;
}

.solar-payment-btn {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    max-width: 300px;
}

.solar-payment-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3);
}

.solar-payment-security {
    text-align: center;
    color: #27ae60;
    font-size: 14px;
}

@media (max-width: 768px) {
    .solar-payment-content {
        padding: 20px;
    }
    
    .solar-payment-header h1 {
        font-size: 24px;
    }
}
</style>

<script src="https://checkout.flutterwave.com/v3.js"></script>
<script type="text/javascript">
    document.getElementById('pay-now-btn').addEventListener('click', function() {
        var paymentData = {
            public_key: '{{ env("FLUTTERWAVE_PUBLIC_KEY") }}',
            tx_ref: '{{ $paymentData["tx_ref"] }}',
            amount: {{ $paymentData['amount'] }},
            currency: '{{ $paymentData["currency"] }}',
            payment_options: '{{ $paymentData["payment_options"] }}',
            redirect_url: '{{ $paymentData["redirect_url"] }}',
            customer: {
                email: '{{ $paymentData["customer"]["email"] }}',
                phone_number: '{{ $paymentData["customer"]["phonenumber"] }}',
                name: '{{ $paymentData["customer"]["name"] }}'
            },
            customizations: {
                title: '{{ $paymentData["customizations"]["title"] }}',
                description: '{{ $paymentData["customizations"]["description"] }}',
                logo: '{{ $paymentData["customizations"]["logo"] }}'
            },
            meta: {
                order_id: {{ $paymentData['meta']['order_id'] }}
            }
        };
        
        FlutterwaveCheckout({
            public_key: paymentData.public_key,
            tx_ref: paymentData.tx_ref,
            amount: paymentData.amount,
            currency: paymentData.currency,
            payment_options: paymentData.payment_options,
            redirect_url: paymentData.redirect_url,
            customer: paymentData.customer,
            customizations: paymentData.customizations,
            meta: paymentData.meta,
            callback: function(data) {
                console.log(data);
            },
            onclose: function() {
                console.log('Payment cancelled');
            }
        });
    });
</script>
@endsection