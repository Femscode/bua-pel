@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/cart.css') }}" />
@endsection

@section('content')
<div class="ct-breadcrumb">
    <a href="/" class="ct-breadcrumb-button"><div class="ct-breadcrumb-item">Home</div></a>
    <div class="ct-breadcrumb-divider">/</div>
    <div class="ct-breadcrumb-wrapper">
        <a href='/shop'><div class="ct-breadcrumb-item">Shop</div></a>
    </div>
    <div class="ct-breadcrumb-divider">/</div>
    <div class="ct-breadcrumb-wrapper"><div class="ct-breadcrumb-item">Cart</div></div>
</div>

<div class="ct-cart">
    <div class="ct-cart-left">
        @if($cartItems->count() > 0)
            @foreach($cartItems as $item)
            <div class="ct-cart-product" data-product-id="{{ $item->product->id }}">
                @php
                    $images = $item->product->image ? json_decode($item->product->image, true) : [];
                    $firstImage = !empty($images) ? $images[0] : null;
                    $imageUrl = $firstImage ? asset('uploads/products/' . $firstImage) : asset('homepage/images/default-product.png');
                @endphp
                <div class="ct-cart-product-img" style="background-image: url('{{ $imageUrl }}'); background-size: cover; background-position: center;"></div>
                <div class="ct-cart-product-info">
                    <div class="ct-cart-product-title"><p class="ct-cart-product-title-text">{{ strtoupper($item->product->name) }}</p></div>
                    @php
                        $displayPrice = method_exists($item->product, 'getFinalPrice')
                            ? $item->product->getFinalPrice()
                            : (($item->product->discount_price && $item->product->discount_price < $item->product->price)
                                ? $item->product->discount_price
                                : $item->product->price);
                    @endphp
                    <div class="ct-cart-product-price">₦{{ number_format($displayPrice, 0) }}</div>
                    <div class="ct-cart-product-quantity">
                        <div class="ct-cart-quantity-decrement" onclick="updateCartQuantity({{ $item->product->id }}, {{ $item->quantity - 1 }})"><div class="ct-cart-quantity-icon"><img src="{{ url('homepage/images/home/minus.svg') }}" alt="minus"/></div></div>
                        <div class="ct-cart-quantity-input">
                            <div class="ct-cart-quantity-value"><div class="ct-cart-quantity-text">{{ $item->quantity }}</div></div>
                        </div>
                        <div class="ct-cart-quantity-increment" onclick="updateCartQuantity({{ $item->product->id }}, {{ $item->quantity + 1 }})"><div class="ct-cart-quantity-icon"><img src="{{ url('homepage/images/home/plus.svg') }}" alt="plus"/></div></div>
                    </div>
                    <div class="ct-cart-product-benefits">
                        <div class="ct-cart-benefit"><div class="ct-cart-benefit-text">FREE SHIPPING</div></div>
                        <div class="ct-cart-benefit-alt"><div class="ct-cart-benefit-text-alt">SECURE PAYMENT</div></div>
                    </div>
                    <div class="ct-cart-product-status">
                        <div class="ct-cart-status-icon"><img src="{{ url('homepage/images/svgs/active.svg') }}" alt="active"/></div>
                        <div class="ct-cart-status-text">In stock</div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-cart" style="text-align: center; padding: 50px;">
                <p style="font-size: 18px; margin-bottom: 20px;">Your cart is empty</p>
                <a href="{{ route('shop') }}" class="continue-shopping" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Continue Shopping</a>
            </div>
        @endif
    </div>
    <div class="ct-cart-right">
        <div class="ct-cart-summary">
            <div class="ct-cart-summary-header">
                <div class="ct-cart-summary-title">Order Summary</div>
                <div class="ct-cart-summary-items">
                    <div class="ct-cart-summary-item">
                        <div class="ct-cart-summary-label">Sub Total:</div>
                        <div class="ct-cart-summary-value">₦{{ number_format($subtotal, 0) }}</div>
                    </div>
                    <div class="ct-cart-summary-item">
                        <div class="ct-cart-summary-label">Discount:</div>
                        <div class="ct-cart-summary-value">-₦0</div>
                    </div>
                    <!-- <div class="ct-cart-summary-item">
                        <div class="ct-cart-summary-label">Tax estimate:</div>
                        <div class="ct-cart-summary-value">₦{{ number_format($tax, 0) }}</div>
                    </div> -->
                </div>
            </div>
            <div class="ct-cart-summary-total">
                <div class="ct-cart-total-label">ORDER TOTAL:</div>
                <div class="ct-cart-total-value">₦{{ number_format($subtotal, 0) }}</div>
            </div>
            <a href="{{ route('checkout') }}" class="ct-cart-checkout-button"><div class="ct-cart-checkout-text">CHECKOUT</div></a>
        </div>
        <div class="ct-cart-promo-jinko" style="background-image:url('homepage/images/home/fashion1.png');background-size:cover; background-color: #f8f9fa;">
            <div class="ct-cart-promo-content">
                <div class="ct-cart-promo-brand">SUMMER SALE</div>
                <div class="ct-cart-promo-text">
                    <div class="ct-cart-promo-title">New Collection</div>
                    <div class="ct-cart-promo-subtitle">Up to 50% Off</div>
                </div>
                <div class="ct-cart-promo-shop"><div class="ct-cart-promo-shop-text">SHOP NOW</div></div>
            </div>
        </div>
        <div class="ct-cart-promo-solis" style="background-image:url('homepage/images/home/fashion2.png');background-size:cover; background-color: #e9ecef;">
            <p class="ct-cart-promo-solis-title">
                <span class="ct-cart-promo-solis-brand">PREMIUM</span>
                <span class="ct-cart-promo-solis-text"> QUALITY ITEMS</span>
            </p>
            <div class="ct-cart-promo-solis-price">
                <div class="ct-cart-promo-solis-from">FROM AS LOW AS</div>
                <div class="ct-cart-promo-solis-amount">₦5,000</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function updateCartQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        if (confirm('Remove this item from cart?')) {
            removeFromCart(productId);
        }
        return;
    }
    
    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error updating cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating cart');
    });
}

function removeFromCart(productId) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error removing item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing item');
    });
}
</script>
@endsection
