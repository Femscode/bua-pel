@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/index2.css') }}" />
<link rel="stylesheet" href="{{ url('homepage/css/index.css') }}" />
@endsection
@section('content')
<div class="quick-links">
    <div class="left">
        <div class="frame">
            <p class="link-SALE-OFF">SHOP NOW, GET UP TO 40% OFF</p>
            <div class="all-categories">
                <div class="category-grid">
                    <div class="category-box" onclick="window.location.href='/shop?category=men-fashion'">
                        <div class="category-content">
                            <div class="category-title">Men's Fashion</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>

                    <div class="category-box" onclick="window.location.href='/shop?category=women-fashion'">
                        <div class="category-content">
                            <div class="category-title">Women's Fashion</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>

                    <div class="category-box" onclick="window.location.href='/shop?category=accessories'">
                        <div class="category-content">
                            <div class="category-title">Accessories</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>

                    <div class="category-box" onclick="window.location.href='/shop?category=shoes'">
                        <div class="category-content">
                            <div class="category-title">Shoes</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>

                    <div class="category-box" onclick="window.location.href='/shop?category=bags'">
                        <div class="category-content">
                            <div class="category-title">Bags</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>

                    <div class="category-box" onclick="window.location.href='/shop?category=beauty'">
                        <div class="category-content">
                            <div class="category-title">Beauty</div>
                            <div class="category-arrow">→</div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <div class="center">
        <div class="carousel-container">
            <div class="carousel-track">
                <div class="carousel-slide carousel-slide-1"></div>
                <div class="carousel-slide carousel-slide-2"></div>
                <div class="carousel-slide carousel-slide-3"></div>
                <div class="carousel-slide carousel-slide-4"></div>
            </div>
            
            <button class="carousel-prev" aria-label="Previous Slide">&#10094;</button>
            <button class="carousel-next" aria-label="Next Slide">&#10095;</button>
            
            <div class="carousel-dots">
                <span class="dot active" data-index="0"></span>
                <span class="dot" data-index="1"></span>
                <span class="dot" data-index="2"></span>
                <span class="dot" data-index="3"></span>
            </div>
        </div>
    </div>

</div>



<div class="deals-of-the-day">
    <div class="frame">
        <div class="div">
            <div class="main">
                <div class="frame-2">
                    <div class="heading-deals-of">DEALS OF THE DAY</div>
                    <div class="link-view-all">View All</div>
                </div>
                <div class="symbol">→</div>
            </div>
            <div class="deal-of-the-day">
                @if($dealOfTheDay)
                <div class="product">
                    <!-- Image Gallery Container -->
                    <div class="image-gallery-container" style="display: flex; gap: 15px; width: 100%; max-width: 500px;">
                        @if($dealOfTheDay->image)
                        @php
                        $images = json_decode($dealOfTheDay->image, true);
                        $images = is_array($images) && !empty($images) ? $images : [];
                        @endphp
                        @endif

                        @if(!empty($images))
                        <!-- Thumbnail Gallery on Left -->
                        <div class="thumbnail-gallery" style="display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;">
                            @foreach($images as $index => $image)
                            @php
                            $thumbImageUrl = asset('uploads/products/' . $image);
                            $borderColor = $index === 0 ? '#007bff' : 'transparent';
                            @endphp
                            <div class="group-prod-png thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                                data-image="{{ $thumbImageUrl }}"
                                style="width: 80px; height: 80px; background-image: url('{{ $thumbImageUrl }}'); background-size: cover; background-position: center; cursor: pointer; border-radius: 8px; border: 2px solid {{ $borderColor }}; transition: all 0.2s ease;"
                                onclick="switchMainImage('{{ $thumbImageUrl }}', this)"></div>
                            @endforeach
                        </div>

                        <!-- Main Preview Image -->
                        @php
                        $mainImageUrl = 'https://best4uarena.com/best4u_files/public/uploads/products/' . $images[0];
                        @endphp
                        <div class="main-image-container" style="flex: 1; height: 350px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <div class="group-png main-product-image" id="mainProductImage"
                                style="width: 100%; height: 100%; background-image: url('{{ $mainImageUrl }}'); background-size: cover; background-position: center; transition: all 0.3s ease;"></div>
                        </div>
                        @else
                        <!-- Fallback when no images available -->
                        <div class="thumbnail-gallery" style="display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;">
                            <!-- No thumbnails when no images available -->
                        </div>

                        @php
                        $defaultImageUrl = asset('homepage/images/default-product.png');
                        @endphp
                        <div class="main-image-container" style="flex: 1; height: 350px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <div class="group-png main-product-image" id="mainProductImage"
                                style="width: 100%; height: 100%; background-color: #f5f5f5; background-image: url('{{ $defaultImageUrl }}'); background-size: cover; background-position: center;"></div>
                        </div>
                        @endif
                    </div>
                    <div class="png">
                        <div class="div-dis-card">
                            <div class="small-save">SAVE</div>
                            @if($dealOfTheDay->discount_price)
                            <div class="heading">₦{{ number_format($dealOfTheDay->price - $dealOfTheDay->discount_price) }}</div>
                            @else
                            <div class="heading">₦150,000</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="frame-3">
                    <div class="frame-4">
                        <a href="{{ route('prd', $dealOfTheDay->slug) }}" class="heading-link-xioma" style="text-decoration: none; color: inherit;">{{ $dealOfTheDay->name }}</a>
                        <div class="text-wrapper">{{ $dealOfTheDay->category->name ?? 'Product' }}</div>
                    </div>
                    <div class="details">
                        <div class="price">
                            @if($dealOfTheDay->discount_price)
                            <div class="heading-2">₦{{ number_format($dealOfTheDay->discount_price) }}</div>
                            <div class="heading-3">₦{{ number_format($dealOfTheDay->price) }}</div>
                            @else
                            <div class="heading-2">₦{{ number_format($dealOfTheDay->price) }}</div>
                            @endif
                        </div>
                        <div class="specs">
                            <div class="frame-5">
                                <div class="list-item"></div>
                                <p class="p">{{ Str::limit($dealOfTheDay->description, 100) }}</p>
                            </div>
                        </div>
                        <div class="frame-6">
                            <div class="link-free-shipping-wrapper">
                                <div class="link-free-shipping">AMAZING DEAL OF THE DAY</div>
                            </div>
                            <!-- <div class="div-wrapper">
                                <div class="link-free-shipping-2">50% INSTALLATION</div>
                            </div> -->
                        </div>
                    </div>
                    <div class="frame-7" data-product-id="{{ $dealOfTheDay->id }}">
                        <div class="link">
                            <a href="{{ route('prd', $dealOfTheDay->slug) }}" class="shop-now">BUY NOW</a>
                        </div>
                        <div class="shop-now-wrapper">
                            <div class="shop-now-2 add-to-cart-btn" data-product-id="{{ $dealOfTheDay->id }}" style="cursor: pointer;">ADD TO CART</div>
                        </div>
                    </div>
                    <div class="frame-8">
                        <div class="hurry-up-promotion">HURRY UP!<br />PROMOTION WILL<br />EXPIRE IN</div>
                        <div class="timer">
                            <div class="div-c-card">
                                <div class="text-wrapper-2">52</div>
                                <div class="small-d">Days</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">12</div>
                                <div class="small-d">Hours</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">01</div>
                                <div class="small-d">Minutes</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">03</div>
                                <div class="small-d">Seconds</div>
                            </div>
                        </div>
                    </div>
                    <div class="div-progress-content">
                        <div class="div-progress">
                            <div class="progressbar"></div>
                        </div>
                        <p class="sold"><span class="span">Sold: </span> <span class="text-wrapper-3">{{ $dealOfTheDay->stock_quantity > 0 ? '26/75' : 'Out of Stock' }}</span></p>
                    </div>
                </div>
                @else
                <div class="product">
                    <div class="div-swiper-wrapper">
                        <div class="group-png"></div>
                        <div class="group-prod-png"></div>
                        <div class="group-prod-png"></div>
                        <div class="group-prod-png"></div>
                        <div class="group-prod-png"></div>
                    </div>
                    <div class="png">
                        <div class="div-dis-card">
                            <div class="small-save">SAVE</div>
                            <div class="heading">₦150,000</div>
                        </div>
                    </div>
                </div>
                <div class="frame-3">
                    <div class="frame-4">
                        <div class="heading-link-xioma">No Deal Available</div>
                        <div class="text-wrapper">Product</div>
                    </div>
                    <div class="details">
                        <div class="price">
                            <div class="heading-2">₦0</div>
                        </div>
                        <div class="specs">
                            <div class="frame-5">
                                <div class="list-item"></div>
                                <p class="p">No products available for deal of the day</p>
                            </div>
                        </div>
                        <div class="frame-6">
                            <div class="link-free-shipping-wrapper">
                                <div class="link-free-shipping">AMAZING DEAL OF THE DAY</div>
                            </div>
                            <!-- <div class="div-wrapper">
                                <div class="link-free-shipping-2">50% INSTALLATION</div>
                            </div> -->
                        </div>
                    </div>
                    <div class="frame-7">
                        <div class="link">
                            <div class="shop-now">BUY NOW</div>
                        </div>
                        <div class="shop-now-wrapper">
                            <div class="shop-now-2">ADD TO CART</div>
                        </div>
                    </div>
                    <div class="frame-8">
                        <div class="hurry-up-promotion">HURRY UP!<br />PROMOTION WILL<br />EXPIRE IN</div>
                        <div class="timer">
                            <div class="div-c-card">
                                <div class="text-wrapper-2">52</div>
                                <div class="small-d">Days</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">12</div>
                                <div class="small-d">Hours</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">01</div>
                                <div class="small-d">Minutes</div>
                            </div>
                            <div class="div-c-card">
                                <div class="text-wrapper-2">03</div>
                                <div class="small-d">Seconds</div>
                            </div>
                        </div>
                    </div>
                    <div class="div-progress-content">
                        <div class="div-progress">
                            <div class="progressbar"></div>
                        </div>
                        <p class="sold"><span class="span">Sold: </span> <span class="text-wrapper-3">0/0</span></p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
    </div>
    <div class="main-2" style="background-image:url('/homepage/images/home/main1.png');background-size:cover">
        <div class="heading-FREE">ENJOY FREE SHIPPING</div>
        <p class="heading-with-no">
            <span class="text-wrapper-4">Shop Items worth over ₦50,000 to enjoy this.</span>
            <span class="text-wrapper-5">&nbsp;</span>
            <span class="text-wrapper-6">*Terms and conditions apply</span>
        </p>
        <div class="link-discover-now">
            <div class="link-discover-now-2">SHOP NOW</div>
        </div>
    </div>
</div>




<div class="trending-products">
    <div class="trending-products-frame">
        <div class="trending-products-heading-wrapper">
            <div class="trending-products-heading">TRENDING PRODUCTS</div>
        </div>
        <div class="trending-products-view-all">View All</div>
    </div>

    <div class="trending-products-tabpanel">
        @foreach($popularProducts->take(24) as $index => $product)
        @php
        $images = $product->image ? json_decode($product->image, true) : [];
        $firstImage = !empty($images) ? $images[0] : null;
        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
        $currentPrice = $hasDiscount ? $product->discount_price : $product->price;
        $oldPrice = $hasDiscount ? $product->price : null;
        
        // Image logic
        $imageUrl = asset('homepage/images/default-product.png');
        if ($firstImage) {
             $imageUrl = 'https://best4uarena.com/best4u_files/public/uploads/products/' . $firstImage;
        }
        @endphp
            <div class="trending-products-group">
                <div class="trending-products-product-frame">
                    <a href="{{ route('prd', $product->slug) }}" class="trending-products-product-img-wrapper" style="text-decoration: none;">
                        <div class="trending-products-product-img" style="background-image: url('{{ $imageUrl }}')"></div>
                    </a>
                    <div class="trending-products-prd">
                        <div class="trending-products-product-title">
                            <a href="{{ route('prd', $product->slug) }}" class="trending-products-product-title-text" style="text-decoration: none; color: inherit;">{{ $product->name }}</a>
                        </div>
                        <div class="trending-products-product-category">{{ $product->category->name ?? 'Product' }}</div>
                    </div>
                    <div class="trending-products-product-price">
                        <div class="trending-products-product-current-price">₦{{ number_format($currentPrice, 0) }}</div>
                        @if($oldPrice)
                        <div class="trending-products-product-old-price">₦{{ number_format($oldPrice, 0) }}</div>
                        @endif
                    </div>
                    <div class="trending-products-product-status">
                        @if($product->stock_quantity > 0)
                        <div class="trending-products-product-status-icon"><img src='{{ url("homepage/images/svgs/active.svg") }}' alt="active" /></div>
                        <div class="trending-products-product-status-text">In stock</div>
                        @else
                        <div class="trending-products-product-status-icon"><img src='{{ url("homepage/images/svgs/cancel.svg") }}' alt="cancel" /></div>
                        <div class="trending-products-product-status-text">Out of stock</div>
                        @endif
                    </div>
                </div>
                <div class="trending-products-product-action" data-product-id="{{ $product->id }}" data-in-stock="{{ $product->stock_quantity > 0 ? '1' : '0' }}">
                    @if($product->stock_quantity > 0)
                    <div class="trending-products-product-action-link add-to-cart-btn" data-product-id="{{ $product->id }}" style="cursor: pointer;">
                        <div class="trending-products-product-action-text">ADD TO CART</div>
                    </div>
                    @else 
                     <div class="trending-products-product-action-link"  style="cursor: pointer; background:grey;color:#fff !important;border-color:white">
                        <div style="color:white" class="trending-products-product-action-text disabled">OUT OF STOCK</div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        
    </div>

    <div style="display: flex; justify-content: center; margin-top: 40px; margin-bottom: 20px;">
        <a href="/shop" style="
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 30px;
            background-color: #004d40;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s;
        " onmouseover="this.style.backgroundColor='#00332a'" onmouseout="this.style.backgroundColor='#004d40'">
            VIEW MORE PRODUCTS
        </a>
    </div>

</div>



@endsection
@section('script')
<script src="{{ asset('homepage/js/image-gallery.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.querySelector('.carousel-track');
        const slides = Array.from(track.children);
        const nextButton = document.querySelector('.carousel-next');
        const prevButton = document.querySelector('.carousel-prev');
        const dotsNav = document.querySelector('.carousel-dots');
        const dots = Array.from(dotsNav.children);

        let currentIndex = 0;

        const updateSlidePosition = () => {
            track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
            
            // Update dots
            dots.forEach(dot => dot.classList.remove('active'));
            if(dots[currentIndex]) {
                dots[currentIndex].classList.add('active');
            }
        };

        const moveToNextSlide = () => {
            if (currentIndex === slides.length - 1) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            updateSlidePosition();
        };

        const moveToPrevSlide = () => {
            if (currentIndex === 0) {
                currentIndex = slides.length - 1;
            } else {
                currentIndex--;
            }
            updateSlidePosition();
        };

        // Event Listeners
        nextButton.addEventListener('click', () => {
            moveToNextSlide();
            resetAutoPlay();
        });

        prevButton.addEventListener('click', () => {
            moveToPrevSlide();
            resetAutoPlay();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateSlidePosition();
                resetAutoPlay();
            });
        });

        // Auto Play
        let autoPlayInterval;

        const startAutoPlay = () => {
            autoPlayInterval = setInterval(moveToNextSlide, 5000); // Change slide every 5 seconds
        };

        const resetAutoPlay = () => {
            clearInterval(autoPlayInterval);
            startAutoPlay();
        };

        startAutoPlay();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the scrolling container and arrow buttons for categories section
        const scrollingContainer = document.querySelector('.scrolling');
        const leftArrow = document.querySelector('.vector-wrapper');
        const rightArrow = document.querySelector('.img-wrapper');

        if (scrollingContainer && leftArrow && rightArrow) {
            // Left arrow click - scroll left
            leftArrow.addEventListener('click', () => {
                scrollingContainer.scrollBy({
                    left: -150,
                    behavior: 'smooth'
                });
            });

            // Right arrow click - scroll right
            rightArrow.addEventListener('click', () => {
                scrollingContainer.scrollBy({
                    left: 150,
                    behavior: 'smooth'
                });
            });
        }

        // Get the brand-new section scrolling container and arrow buttons
        const brandScrollingContainer = document.querySelector('.brand-new-tabpanel');
        const brandLeftArrows = document.querySelectorAll('.brand-new-vector-wrapper');

        if (brandScrollingContainer && brandLeftArrows.length >= 2) {
            // First arrow (left arrow) - scroll left
            brandLeftArrows[0].addEventListener('click', () => {
                brandScrollingContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            });

            // Second arrow (right arrow) - scroll right
            brandLeftArrows[1].addEventListener('click', () => {
                brandScrollingContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });
        }

        // Newsletter form submission
        const newsletterForm = document.getElementById('newsletterForm');
        const newsletterMessage = document.getElementById('newsletterMessage');

        if (newsletterForm) {
            newsletterForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const emailInput = this.querySelector('.site-footer-newsletter-input');
                const email = emailInput.value.trim();

                if (!email) {
                    showMessage('Please enter your email address.', 'error');
                    return;
                }

                // Show loading state
                const submitButton = this.querySelector('.site-footer-newsletter-button');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Subscribing...';
                submitButton.disabled = true;

                try {
                    // Replace this URL with your actual newsletter API endpoint
                    const response = await fetch('/api/newsletter/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    });

                    if (response.ok) {
                        showMessage('Thank you for subscribing to our newsletter!', 'success');
                        emailInput.value = '';
                    } else {
                        showMessage('Something went wrong. Please try again later.', 'error');
                    }
                } catch (error) {
                    showMessage('Network error. Please check your connection and try again.', 'error');
                } finally {
                    // Reset button state
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                }
            });
        }

        function showMessage(message, type) {
            if (newsletterMessage) {
                newsletterMessage.textContent = message;
                newsletterMessage.className = `site-footer-newsletter-message ${type}`;

                // Hide message after 5 seconds
                setTimeout(() => {
                    newsletterMessage.className = 'site-footer-newsletter-message';
                }, 5000);
            }
        }
    });
</script>

<!-- Cart functionality -->
<script src="{{ asset('js/cart.js') }}"></script>
@endsection
