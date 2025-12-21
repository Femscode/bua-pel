@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/product-details.css') }}" />
<script src="{{ url('homepage/js/image-gallery.js') }}"></script>
@endsection

@section('content')
<div class="breadcrumb">
    <button class="breadcrumb-button">
        <a href="/"> <div class="breadcrumb-item">Home</div></a>
    </button>
    <div class="breadcrumb-divider">/</div>
    <div class="breadcrumb-wrapper">
        <a href="{{ route('shop') }}">
            <div class="breadcrumb-item">Shop</div>
        </a>
    </div>
    <div class="breadcrumb-divider">/</div>

    <div class="breadcrumb-wrapper">
        <a href="{{ route('prd', $product->id) }}">
            <p class="breadcrumb-current">{{ $product->name }}</p>
        </a>
    </div>
</div>

<div class="pd-product-details">
    <div class="pd-product-details-container">
        <!-- Your existing PHP Blade code with updated class names below -->
        <div class="pd-product-wrapper">

            <!-- Image Gallery + Main Image -->
            <div class="pd-gallery">
                @if($product && $product->image)
                @php
                $images = json_decode($product->image, true);
                $images = is_array($images) ? $images : [$product->image];
                $firstImage = $images[0] ?? (is_string($product->image) ? $product->image : null);
                @endphp

                @if(count($images) > 1)
                <div class="pd-thumbnails">
                    @foreach($images as $index => $image)
                    <div class="pd-thumb {{ $index === 0 ? 'active' : '' }}"
                    data-image="{{ 'https://best4uarena.com/best4u_files/public/uploads/products/' . $image }}"
                    onclick="switchMainImage('{{ 'https://best4uarena.com/best4u_files/public/uploads/products/' . $image }}', this)">
                    <img src="{{ 'https://best4uarena.com/best4u_files/public/uploads/products/' . $image }}"
                        alt="Thumbnail">
                </div>
                    @endforeach
                </div>
                @endif

                <div class="pd-main-image-wrapper">
                    <div class="pd-main-image" id="mainProductImage" 
                        data-initial-image="{{ $firstImage ? 'https://best4uarena.com/best4u_files/public/uploads/products/' . $firstImage : asset('homepage/images/default-product.png') }}">

                        @if($product?->discount_price && $product->discount_price < $product->price)
                            <div class="pd-discount-badge">
                                <span>SAVE</span>
                                <strong>₦{{ number_format($product->price - $product->discount_price) }}</strong>
                            </div>
                            @endif
                    </div>
                </div>
                @else
                <div class="pd-main-image-wrapper">
                    <div class="pd-main-image" id="mainProductImage" data-initial-image="{{ asset('homepage/images/default-product.png') }}"></div>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="pd-info">
                <div class="pd-title-section">
                    <h1 class="pd-title">{{ $product->name ?? 'Product Name' }}</h1>
                    <p class="pd-category">{{ $product?->category?->name ?? 'Product Category' }}</p>
                </div>

                <div class="pd-price">
                    @if($product?->discount_price && $product->discount_price < $product->price)
                        <span class="pd-current-price">₦{{ number_format($product->discount_price) }}</span>
                        <span class="pd-old-price">₦{{ number_format($product->price) }}</span>
                        @else
                        <span class="pd-current-price">₦{{ number_format($product->price ?? 0) }}</span>
                        @endif
                </div>

                @if($product?->short_description)
                <p class="pd-short-desc">{{ $product->short_description }}</p>
                @endif

                @if($product?->description)
                <p class="pd-description">{{ Str::limit($product->description, 200) }}</p>
                @endif

                <div class="pd-benefits">
                    <div class="pd-benefit"><strong>FREE SHIPPING</strong></div>
                    <div class="pd-benefit pd-benefit-alt"><strong>SECURE PAYMENT</strong></div>
                </div>

                <div class="pd-contact">
                    <div class="pd-contact-label">Quick Order 24/7</div>
                    <a href="tel:+2348089228381" class="pd-contact-number">+(234) 808 922 8381</a>
                </div>
            </div>
        </div>

        <!-- Sticky Checkout Bar (Mobile) / Sidebar (Desktop) -->
        <div class="pd-checkout-sidebar">
            <div class="pd-checkout-summary">
                <div class="pd-total">
                    <span>TOTAL PRICE:</span>
                    <strong class="pd-total-amount">
                        @if($product?->discount_price && $product->discount_price < $product->price)
                            ₦{{ number_format($product->discount_price) }}
                            @else
                            ₦{{ number_format($product->price ?? 0) }}
                            @endif
                    </strong>
                </div>

                <div class="pd-stock">
                    @if($product?->stock_quantity > 0)
                    <span class="pd-in-stock">✔ In stock</span>
                    @else
                    <span class="pd-out-of-stock">✘ Out of stock</span>
                    @endif
                </div>
            </div>

            <div class="pd-actions">
                <div class="pd-quantity">
                    <button class="pd-qty-btn decrement">-</button>
                    <span class="pd-qty-value">1</span>
                    <button class="pd-qty-btn increment">+</button>
                </div>
 <button class="pd-btn pd-btn-cart add-to-cart-btn" data-product-id="{{ $product->id }}">
                    ADD TO CART
                </button>
                <a href='/cart' style="text-align:center" class="pd-btn pd-btn-checkout">PROCEED TO CHECKOUT</a>
               
            </div>

            <div class="pd-payment">
                <p class="pd-payment-label">Guaranteed Safe Checkout</p>
                <div class="pd-payment-logos">
                    <img src="{{ url('homepage/images/home/visa.png') }}" alt="Visa">
                    <img src="{{ url('homepage/images/home/mastercard.png') }}" alt="Mastercard">
                </div>
            </div>
        </div>
    </div>

   
    <div class="pd-description-section">
        <div class="pd-description-tabs">
            <div class="pd-description-tab" data-tab="description">DESCRIPTION</div>
            <!-- <div class="pd-description-tab" data-tab="additional">SPECIFICATIONS</div> -->
        </div>
        <div class="pd-description-content">
            <div class="pd-description-text" id="description-content">
                @if($product && $product->description)
                <p>{!! nl2br(e($product->description)) !!}</p>
               
               
                @endif
            </div>
            <div class="pd-description-text" id="additional-content" style="display: none;">
                @if($product && $product->additional_info)
                <p>{!! nl2br(e($product->additional_info)) !!}</p>
                @else
                <p>
                 No Specifications
                </p>
                @endif
            </div>
            <div class="pd-description-image" data-image="{{ url('homepage/images/home/product-details.png') }}"></div>
        </div>
    </div>
</div>

 <!-- Simple JS for thumbnail switching & quantity -->
    <script>
        function switchMainImage(src, el) {
            document.querySelector('.pd-main-image').style.backgroundImage = `url('${src}')`;
            document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
        }

        // Quantity controls
        document.querySelectorAll('.pd-qty-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                let value = parseInt(document.querySelector('.pd-qty-value').textContent);
                if (btn.classList.contains('increment')) value++;
                else if (value > 1) value--;
                document.querySelector('.pd-qty-value').textContent = value;
            });
        });

        // Initialize main image from data attribute to avoid inline Blade in CSS
        (function initMainImage() {
            const mainEl = document.getElementById('mainProductImage') || document.querySelector('.pd-main-image');
            if (mainEl && mainEl.dataset && mainEl.dataset.initialImage) {
                mainEl.style.backgroundImage = `url('${mainEl.dataset.initialImage}')`;
            }
        })();

        // Initialize description image background
        (function initDescriptionImage() {
            document.querySelectorAll('.pd-description-image').forEach(el => {
                const src = el.dataset && el.dataset.image;
                if (src) el.style.backgroundImage = `url('${src}')`;
            });
        })();
    </script>
    <style>
        /* Reset & Base */
        .opd-product-details * {
            box-sizing: border-box;
        }

        .opd-product-details img {
            max-width: 100%;
            display: block;
        }

        /* Main container */
        .opd-product-details {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Flex wrapper - switches layout at desktop */
        .opd-product-wrapper {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        @media (min-width: 992px) {
            .opd-product-wrapper {
                flex-direction: row;
            }

            .opd-gallery {
                flex: 1;
            }

            .opd-info {
                flex: 1;
            }
        }

        /* Gallery */
        .opd-gallery {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .opd-thumbnails {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding: 0.5rem 0;
        }

        .opd-thumb {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .opd-thumb.active,
        .opd-thumb:hover {
            border-color: #004d40;
        }

        .opd-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .opd-main-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .opd-main-image {
            height: 400px;
            background-size: cover;
            background-position: center;
            background-color: #f5f5f5;
        }

        .opd-discount-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #ff1744;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(255, 23, 68, 0.4);
        }

        .opd-discount-badge span {
            display: block;
            font-size: 0.75rem;
        }

        /* Info Section */
        .opd-title {
            font-size: 1.8rem;
            margin: 0;
            line-height: 1.2;
            font-weight: 700;
        }

        .opd-category {
            color: #666;
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }

        .opd-price {
            margin: 1rem 0;
        }

        .opd-current-price {
            font-size: 2.2rem;
            font-weight: 800;
            color: #004d40;
        }

        .opd-old-price {
            text-decoration: line-through;
            color: #999;
            margin-left: 1rem;
            font-size: 1.3rem;
        }

        .opd-short-desc {
            font-weight: 600;
            margin: 1.5rem 0;
        }

        .opd-description {
            color: #444;
            line-height: 1.6;
        }

        .opd-benefits {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }

        .opd-benefit {
            background: #e8f5e9;
            color: #004d40;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: bold;
        }

        .opd-benefit-alt {
            background: #fff3e0;
            color: #ff8f00;
        }

        .opd-contact {
            margin-top: 2rem;
            text-align: center;
            padding: 1rem;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .opd-contact-label {
            font-size: 0.9rem;
            color: #666;
        }

        .opd-contact-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #004d40;
            text-decoration: none;
        }

        /* Checkout Sidebar - Sticky on mobile */
        .opd-checkout-sidebar {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        @media (min-width: 992px) {
            .opd-checkout-sidebar {
                position: sticky;
                top: 1.5rem;
                height: fit-content;
                margin-top: 0;
                flex: 0 0 360px;
            }
        }

        .opd-total {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .opd-total-amount {
            color: #004d40;
        }

        .opd-stock {
            margin-bottom: 1.5rem;
        }

        .opd-in-stock {
            color: #004d40;
            font-weight: bold;
        }

        .opd-out-of-stock {
            color: #f44336;
            font-weight: bold;
        }

        .opd-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .opd-quantity {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            border-radius: 12px;
            width: fit-content;
            margin: 0 auto;
        }

        .opd-qty-btn {
            width: 48px;
            height: 48px;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .opd-qty-value {
            width: 60px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .opd-btn {
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .opd-btn-checkout {
            background: #004d40;
            color: white;
        }

        .opd-btn-checkout:hover {
            background: #00b140;
        }

        .opd-btn-cart {
            background: #333;
            color: white;
        }

        .opd-btn-cart:hover {
            background: #111;
        }

        .opd-payment {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .opd-payment-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .opd-payment-logos img {
            height: 30px;
            margin: 0 0.5rem;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .opd-main-image {
                height: 300px;
            }

            .opd-title {
                font-size: 1.5rem;
            }

            .opd-current-price {
                font-size: 1.8rem;
            }
        }
    </style>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tab toggle functionality
        const tabs = document.querySelectorAll('.solar-product-description-tab');
        const descriptionContent = document.getElementById('description-content');
        const additionalContent = document.getElementById('additional-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('solar-product-description-tab-active'));
                // Add active class to clicked tab
                tab.classList.add('solar-product-description-tab-active');

                // Toggle content visibility with fade effect
                if (tab.dataset.tab === 'description') {
                    descriptionContent.style.opacity = '0';
                    additionalContent.style.display = 'none';
                    setTimeout(() => {
                        descriptionContent.style.display = 'block';
                        descriptionContent.style.opacity = '1';
                    }, 200);
                } else {
                    additionalContent.style.opacity = '0';
                    descriptionContent.style.display = 'none';
                    setTimeout(() => {
                        additionalContent.style.display = 'block';
                        additionalContent.style.opacity = '1';
                    }, 200);
                }
            });
        });

        // Quantity increment/decrement functionality
        const decrementBtn = document.querySelector('.solar-product-details-quantity-decrement');
        const incrementBtn = document.querySelector('.solar-product-details-quantity-increment');
        const quantityValue = document.querySelector('.solar-product-details-quantity-value');

        if (decrementBtn && incrementBtn && quantityValue) {
            decrementBtn.addEventListener('click', () => {
                let value = parseInt(quantityValue.textContent);
                if (value > 1) {
                    quantityValue.textContent = value - 1;
                }
            });

            incrementBtn.addEventListener('click', () => {
                let value = parseInt(quantityValue.textContent);
                quantityValue.textContent = value + 1;
            });
        }
    });
</script>

<!-- Cart functionality -->
<script src="{{ asset('js/cart.js') }}"></script>
@endsection
