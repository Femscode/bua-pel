@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/checkout.css') }}" />
@endsection

@section('content')
<div class="ck-breadcrumb">
    <button class="ck-breadcrumb-button">
        <div class="ck-breadcrumb-item">Home</div>
    </button>
    <div class="ck-breadcrumb-divider">/</div>
    <div class="ck-breadcrumb-wrapper">
        <div class="ck-breadcrumb-item">Shop</div>
    </div>
    <div class="ck-breadcrumb-divider">/</div>
    <div class="ck-breadcrumb-wrapper">
        <div class="ck-breadcrumb-item">Cart</div>
    </div>
    <div class="ck-breadcrumb-divider">/</div>
    <div class="ck-breadcrumb-wrapper">
        <p class="ck-breadcrumb-current">Checkout</p>
    </div>
</div>

<div class="ck-checkout">
    <h1 class="ck-checkout-title">CHECKOUT</h1>
    <div class="ck-checkout-content">
        <div class="ck-checkout-billing">
            <h2 class="ck-checkout-billing-header">Billing Details</h2>
            
            @if(session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="checkout-form" class="ck-checkout-billing-form" action="{{ route('order.place') }}" method="POST">
                @csrf
                <div class="ck-checkout-form-group">
                    <label class="ck-checkout-label" for="full-name">
                        <span class="ck-checkout-label-text">Full Name</span>
                        <span class="ck-checkout-required">*</span>
                    </label>
                    <input type="text" id="full-name" name="full_name" class="ck-checkout-input" value="{{ old('full_name', Auth::user() ? Auth::user()->name : '') }}" required>
                </div>
                <div class="ck-checkout-form-group">
                    <label class="ck-checkout-label" for="full-address">
                        <span class="ck-checkout-label-text">Full Address</span>
                        <span class="ck-checkout-required">*</span>
                    </label>
                    <textarea id="full-address" name="full_address" class="ck-checkout-textarea" rows="3" required>{{ old('full_address', Auth::user()->address ?? '') }}</textarea>
                </div>
                <div class="ck-checkout-form-group">
                    <label class="ck-checkout-label" for="phone">
                        <span class="ck-checkout-label-text">Phone Number</span>
                        <span class="ck-checkout-required">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" class="ck-checkout-input" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                </div>
                <div class="ck-checkout-form-group">
                    <label class="ck-checkout-label" for="email">
                        <span class="ck-checkout-label-text">Email Address</span>
                        <span class="ck-checkout-required">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="ck-checkout-input" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                </div>
                @guest
                <div class="ck-checkout-account">
                    <input type="checkbox" id="create-account" name="create_account" class="ck-checkout-checkbox" {{ old('create_account') ? 'checked' : '' }}>
                    <label for="create-account" class="ck-checkout-account-label">Create an account?</label>
                </div>
                <div id="password-fields" style="display: none;">
                    <div class="ck-checkout-form-group">
                        <label class="ck-checkout-label" for="password">
                            <span class="ck-checkout-label-text">Password</span>
                            <span class="ck-checkout-required">*</span>
                        </label>
                        <input type="password" id="password" name="password" class="ck-checkout-input">
                    </div>
                    <div class="ck-checkout-form-group">
                        <label class="ck-checkout-label" for="password-confirmation">
                            <span class="ck-checkout-label-text">Confirm Password</span>
                            <span class="ck-checkout-required">*</span>
                        </label>
                        <input type="password" id="password-confirmation" name="password_confirmation" class="ck-checkout-input">
                    </div>
                </div>
                @endguest
                <div class="ck-checkout-additional">
                    <h3 class="ck-checkout-additional-header">Additional Information</h3>
                    <div class="ck-checkout-form-group">
                        <label class="ck-checkout-label" for="order-notes">
                            <span class="ck-checkout-label-text">Order Notes</span>
                            <span class="ck-checkout-optional">(Optional)</span>
                        </label>
                        <textarea id="order-notes" name="order_notes" class="ck-checkout-textarea" placeholder="Note about your order, e.g. special note for delivery">{{ old('order_notes') }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="ck-checkout-order">
            <h2 class="ck-checkout-order-header">Your Order</h2>
            <div class="ck-checkout-order-details">
                <div class="ck-checkout-order-card">
                    <div class="ck-checkout-order-heading">
                        <div class="ck-checkout-order-product">PRODUCT</div>
                        <div class="ck-checkout-order-subtotal">SUBTOTAL</div>
                    </div>
                    <div class="ck-checkout-order-items">
                        @if($cartItems->count() > 0)
                            @foreach($cartItems as $item)
                            <div class="ck-checkout-product">
                                @php
                            $images = $item->product->image ? json_decode($item->product->image, true) : [];
                            $firstImage = !empty($images) ? $images[0] : null;
                            $imageUrl = $firstImage ? 'https://best4uarena.com/best4u_files/public/uploads/products/' . $firstImage : asset('homepage/images/default-product.png');
                        @endphp
                        <div class="ck-checkout-product-img" style="background-image: url('{{ $imageUrl }}'); background-size: cover; background-position: center;"></div>
                                <div class="ck-checkout-product-info">
                                    <p class="ck-checkout-product-name">{{ strtoupper($item->product->name) }}</p>
                                    <div class="ck-checkout-product-quantity">x {{ $item->quantity }}</div>
                                </div>
                                @php
                                    $unitPrice = method_exists($item->product, 'getFinalPrice')
                                        ? $item->product->getFinalPrice()
                                        : (($item->product->discount_price && $item->product->discount_price < $item->product->price)
                                            ? $item->product->discount_price
                                            : $item->product->price);
                                @endphp
                                <div class="ck-checkout-product-subtotal">₦{{ number_format($unitPrice * $item->quantity, 0) }}</div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-checkout" style="text-align: center; padding: 20px;">
                                <p>No items in cart</p>
                                <a href="{{ route('shop') }}">Continue Shopping</a>
                            </div>
                        @endif
                    </div>
                    <!-- <div class="ck-checkout-order-shipping">
                        <div class="ck-checkout-shipping-text">Standard Shipping</div>
                        <div class="ck-checkout-shipping-cost">₦{{ number_format($shippingCost, 0) }}</div>
                    </div> -->
                </div>
                <div class="ck-checkout-order-total">
                    <div class="ck-checkout-total-label">Order Total</div>
                    <div class="ck-checkout-total-amount">₦{{ number_format($subtotal, 0) }}</div>
                </div>
            </div>
            <div class="ck-checkout-payment">
                <div class="ck-checkout-payment-options">
                    <div class="ck-checkout-payment-option">
                        <input disabled type="radio" id="whatsapp" name="payment_method" value="whatsapp" class="ck-checkout-radio" form="checkout-form">
                        <label for="whatsapp" class="ck-checkout-payment-label">Pay Online</label>
                    </div>
                    <div class="ck-checkout-payment-option">
                        <input checked type="radio" id="bank-transfer" name="payment_method" value="bank_transfer" class="ck-checkout-radio" form="checkout-form">
                        <label for="bank-transfer" class="ck-checkout-payment-label">Bank Transfer</label>
                    </div>
                    
                    <!-- Bank Transfer Details -->
                    <div id="bank-transfer-details" style="display: block; margin-top: 20px;">
                        <div style="background: linear-gradient(145deg, #0b1a2b 0%, #003177 100%); color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <div style="text-align: center; margin-bottom: 20px;">
                                <h4 style="margin: 0; font-size: 1.2rem; font-weight: 700; letter-spacing: 0.5px; color: #fff;">BANK TRANSFER INSTRUCTIONS</h4>
                                <div style="width: 50px; height: 3px; background: #e8dcb5; margin: 10px auto;"></div>
                                <p style="font-size: 0.95rem; opacity: 0.9;">Please transfer the total amount to either account below:</p>
                            </div>
                            
                            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">BANK</span>
                                    <span style="font-weight: 600;">OPAY</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">ACCOUNT NAME</span>
                                    <span style="font-weight: 600; text-align: right;">Ayodeji comfort</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed rgba(255,255,255,0.3); padding-top: 8px; margin-top: 8px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">ACCOUNT NUMBER</span>
                                    <span style="font-family: 'Courier New', monospace; font-size: 1.2rem; font-weight: 700; letter-spacing: 1px;">9041496142</span>
                                </div>
                            </div>

                            <div style="text-align: center; font-weight: bold; font-size: 0.8rem; margin: 10px 0; opacity: 0.7;">— OR —</div>

                            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">BANK</span>
                                    <span style="font-weight: 600;">FIDELITY</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">ACCOUNT NAME</span>
                                    <span style="font-weight: 600; text-align: right;">Comfort Yetunde Ayodeji</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed rgba(255,255,255,0.3); padding-top: 8px; margin-top: 8px;">
                                    <span style="font-size: 0.9rem; opacity: 0.8;">ACCOUNT NUMBER</span>
                                    <span style="font-family: 'Courier New', monospace; font-size: 1.2rem; font-weight: 700; letter-spacing: 1px;">6328577012</span>
                                </div>
                            </div>
                            
                            <div style="margin-top: 20px; font-size: 0.85rem; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 6px;">
                                <p style="margin: 0;">Use your <strong>Full Name</strong> as the payment reference.</p>
                                <p style="margin: 5px 0 0 0;">After transfer, click <strong>"PLACE ORDER"</strong> to send proof via WhatsApp.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" form="checkout-form" class="ck-checkout-place-order">
                    <span class="ck-checkout-place-order-text">PLACE ORDER</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('checkout-form');
        form.addEventListener('submit', (e) => {
            // Allow normal form submission to Laravel backend
            // Remove preventDefault to enable actual form submission
        });

        const radios = document.querySelectorAll('.ck-checkout-radio');
        const bankDetails = document.getElementById('bank-transfer-details');

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                // Toggle Bank Details visibility
                if (radio.value === 'bank_transfer' && radio.checked) {
                    bankDetails.style.display = 'block';
                } else {
                    bankDetails.style.display = 'none';
                }
            });
        });

        // Handle create account checkbox
        const createAccountCheckbox = document.getElementById('create-account');
        const passwordFields = document.getElementById('password-fields');
        
        if (createAccountCheckbox && passwordFields) {
            createAccountCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordFields.style.display = 'block';
                    document.getElementById('password').required = true;
                    document.getElementById('password-confirmation').required = true;
                } else {
                    passwordFields.style.display = 'none';
                    document.getElementById('password').required = false;
                    document.getElementById('password-confirmation').required = false;
                }
            });
        }
    });
</script>
@endsection
