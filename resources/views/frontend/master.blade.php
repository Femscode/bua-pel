<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ url('homepage/css/global.css') }}" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="{{ url('homepage/css/master.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-heading: 'Open Sans', sans-serif;
            --font-body: 'Open Sans', sans-serif;
            --primary-dark: #004d40;
            --primary-light: #e8dcb5;
            --text-dark: #333333;
            --text-light: #ffffff;
        }
        
        body {
            font-family: var(--font-body);
        }

        h1, h2, h3, h4, h5, h6, .group-heading, .heading-deals-of, .solar-inverters-heading {
            font-family: var(--font-heading);
        }
        
        /* Override Global Colors */
        .top-nav {
            background-color: var(--primary-dark) !important;
        }
        
        .top-nav .small {
            background-color: var(--primary-light) !important;
            color: var(--primary-dark) !important;
            font-weight: bold;
        }
        
        .mid-nav .nav-links a {
            color: var(--primary-dark);
        }
        
        .mid-nav .nav-links a:hover {
            color: #00796b; /* Lighter shade for hover */
        }
        
        .footer {
            background-color: #1a1a1a; /* Dark footer */
            color: white;
        }
        
        .company-info h3 {
            color: var(--primary-light);
        }
        
        .footer-column h4 {
            color: var(--primary-light);
        }

        .opd-btn-checkout {
            background-color: var(--primary-dark) !important;
        }

        .opd-current-price {
            color: var(--primary-dark) !important;
        }
        
        /* Add more overrides as we discover them */
        
        /* Nav Section Overrides */
        .nav {
            background-image: none !important;
            background-color: var(--primary-dark) !important;
        }
        
        .nav .shipping-info .link-free-shipping,
        .nav .shipping-info .link-days-money {
            color: var(--primary-light) !important;
        }
        
        .nav .options {
            background: var(--primary-light) !important;
        }
        
        .nav .options:hover {
            background: #d4c8a8 !important; /* Slightly darker for hover */
        }
        
        .nav .text-wrapper {
            color: var(--primary-dark) !important;
        }
        
        .nav .div-search-cat {
            border: 2px solid var(--primary-light) !important;
        }
        
        .nav .div-search-cat:focus-within {
            border-color: var(--primary-light) !important;
            box-shadow: 0 0 10px rgba(232, 220, 181, 0.5) !important;
        }
    </style>
    @yield('header')
</head>

<body>
    <header class="header">
        <!-- Top Nav -->
        <div class="top-nav">
            <div class="frame">
                <div class="small">
                    <img src="{{ url('homepage/images/phone.svg') }}" width="12" height="12" alt="phone" />
                    Hotline 24/7
                </div>
                <div class="phone-numbers">+(234) 808 922 8381</div>
            </div>
            <div class="frame">
                <div>Naira ▾</div>
                <div>Eng ▾</div>
            </div>
        </div>

        <!-- Mid Nav -->
        <div class="mid-nav">
            <a href='/'><img src="{{ url('homepage/images/ologo.png') }}" alt="Best4UArena" class="logo" /></a>

            <!-- Mobile cart and hamburger -->
            <div class="mobile-nav-controls">
                <a href="{{ route('cart') }}" class="mobile-cart">
                    <div class="cart-icon">
                        <img src="{{ url('homepage/images/cart.svg') }}" width="20" alt="cart" />
                        <span class="cart-count">0</span>
                    </div>
                </a>
                <button class="menu-toggle" aria-label="Toggle Menu">
                    <span class="hamburger-line top"></span>
                    <span class="hamburger-line middle"></span>
                    <span class="hamburger-line bottom"></span>
                </button>
            </div>

            <nav class="nav-links">
                <a href="/">Home</a>
                <a href="/shop">Shop</a>
                <a href="/contact-us">Contact</a>
                <a href="/about-us">About Us</a>
                  @if(auth()->check())
                    <a href="/dashboard">Dashboard</a>
                    <a href="/user/orders">Orders</a>
                @endif
            </nav>

            <div class="actions">
                <div>
                    @if(auth()->check())
                        <div class="welcome">Welcome, {{ auth()->user()->name }}</div>
                         <a href="/dashboard" class="login">Dashboard</a>
                    @else
                        <div class="welcome">Welcome To Best4UArena</div>
                        <a href="/login" class="login">LOG IN / REGISTER</a>
                    @endif
                </div>
                <a href="{{ route('cart') }}" class="cart">
                    <div class="cart-icon">
                        <img src="{{ url('homepage/images/cart.svg') }}" width="20" alt="cart" />
                        <span class="cart-count">0</span>
                    </div>
                    <div>
                        <div class="small-cart">Cart</div>
                        <!-- <div class="price">₦0</div> -->
                    </div>
                </a>
            </div>
        </div>

        <div class="nav">
            <form action="{{ route('search') }}" method="GET" class="div-search-cat">
                <div class="input">
                    <input class="div-placeholder" name="q" placeholder="Search for fashion, beauty..." type="text" value="{{ request('q') }}" />
                </div>
                <div class="options">
                    <button type="submit" class="frame" style="background: none; border: none; cursor: pointer;">
                        <div class="text-wrapper">Search</div>
                    </button>
                </div>
            </form>

            <div class="shipping-info">
                <div class="link-free-shipping">FREE SHIPPING OVER ₦500,000</div>
                <div class="link-days-money">30 DAYS RETURN POLICY</div>
            </div>
          
        </div>
    </header>

    <main>
        @yield('content')
    </main>


    <footer class="footer">
        <div class="footer-content">
            <div class="company-info">
                <h3>BEST4UARENA</h3>
                <p class="hotline">HOTLINE 24/7</p>
                <p class="phone">+(234) 808 922 8381</p>
                <p class="address">Lagos, Nigeria</p>
                <p class="email">info@best4uarena.com</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter"><img src="{{ url('homepage/images/x.svg') }}" alt="x"/></a>
                    <a href="#" aria-label="Facebook"><img src="{{ url('homepage/images/facebook.svg') }}" alt="x"/></a>
                    <a href="#" aria-label="Instagram"><img src="{{ url('homepage/images/instagram.svg') }}" alt="x"/></a>
                    <a href="#" aria-label="YouTube"><img src="{{ url('homepage/images/youtube.svg') }}" alt="x"/></a>
                </div>
            </div>
            <div class="product-columns">
                <div class="footer-column">
                    <div>
                        <h4>Women's Fashion</h4>
                        <ul>
                            <li><a href="#">Dresses</a></li>
                            <li><a href="#">Tops</a></li>
                            <li><a href="#">Shoes</a></li>
                            <li><a href="#">Accessories</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-column">
                    <div>
                        <h4>Men's Fashion</h4>
                        <ul>
                            <li><a href="#">Shirts</a></li>
                            <li><a href="#">Trousers</a></li>
                            <li><a href="#">Shoes</a></li>
                            <li><a href="#">Watches</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-column">
                    <div>
                        <h4>Beauty & Care</h4>
                        <ul>
                            <li><a href="#">Skincare</a></li>
                            <li><a href="#">Makeup</a></li>
                            <li><a href="#">Hair Care</a></li>
                            <li><a href="#">Fragrances</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-column">
                    <div>
                        <h4>Customer Service</h4>
                        <ul>
                            <li><a href="/contact-us">Contact Us</a></li>
                            <li><a href="/about-us">About Us</a></li>
                            <li><a href="#">Shipping Policy</a></li>
                            <li><a href="#">Returns & Exchanges</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- <div class="footer-column">
            <div>
            <h4>Company</h4>
            <ul>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">About Us</a></li>
            </ul>
            </div>
        </div> -->
        </div>
        <div class="footer-bottom">
            <p style='color:#fff'>© 2025 Best4UArena. All Rights Reserved</p>
            <div class="payment-methods">
                <span><img src="{{ url('homepage/images/paypal.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/visa.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/mastercard.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/paypal.svg') }}" alt="x"/></span>
            </div>
        </div>
    </footer>


    <!-- Cart Notification Toast -->
    <div id="cart-notification" class="cart-notification hidden">
        <div class="cart-notification-content">
            <div class="cart-notification-icon">
                <img src="{{ url('homepage/images/cart.svg') }}" width="24" alt="cart" />
            </div>
            <div class="cart-notification-text">
                <div class="cart-notification-title">Product added to cart!</div>
                <div class="cart-notification-subtitle">Item successfully added to your cart</div>
            </div>
            <div class="cart-notification-actions">
                <button class="cart-notification-close" onclick="hideCartNotification()">&times;</button>
                <a href="{{ route('cart') }}" class="cart-notification-view-cart">View Cart</a>
            </div>
        </div>
    </div>

    <script>
        // Toggle menu for mobile
        document.querySelector(".menu-toggle").addEventListener("click", function() {
            this.classList.toggle("active");
            document.querySelector(".nav-links").classList.toggle("active");
            document.querySelector(".actions").classList.toggle("active");
        });

        // Cart notification functions
        function showCartNotification(productName = '') {
            const notification = document.getElementById('cart-notification');
            const titleElement = notification.querySelector('.cart-notification-title');
            
            if (productName) {
                titleElement.textContent = `${productName} added to cart!`;
            } else {
                titleElement.textContent = 'Product added to cart!';
            }
            
            notification.classList.remove('hidden');
            notification.classList.add('show');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideCartNotification();
            }, 5000);
        }

        function hideCartNotification() {
            const notification = document.getElementById('cart-notification');
            notification.classList.remove('show');
            notification.classList.add('hidden');
        }

        // Update cart count
        function updateCartCount(count) {
            const cartCounts = document.querySelectorAll('.cart-count');
            cartCounts.forEach(element => {
                element.textContent = count;
            });
        }

        // Cart notification functions (used by cart.js)
        window.showCartNotification = showCartNotification;
        window.updateCartCount = updateCartCount;
    </script>
    @yield('script')
</body>

</html>