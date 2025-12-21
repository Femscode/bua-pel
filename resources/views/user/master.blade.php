<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ url('homepage/css/global.css') }}" />
    <link rel="stylesheet" href="styleguide.css" />
    <link rel="stylesheet" href="{{ url('homepage/css/user-master.css') }}" />
    <link rel="stylesheet" href="{{ url('css/user-orders.css') }}" />
    <link rel="stylesheet" href="{{ url('css/user-dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
            
            /* Map existing variables in user-master.css to brand colors */
            --primary-color: #004d40;
            --primary-blue: #004d40;
            --accent-orange: #d67715;
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

        /* Dashboard Specific Overrides */
        .dashboard-welcome {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #00695c 100%) !important;
            box-shadow: 0 8px 32px rgba(0, 77, 64, 0.2) !important;
        }

        .stat-card.total-orders { --card-accent: var(--primary-dark) !important; }
        .stat-card.total-orders .stat-icon { background: linear-gradient(135deg, var(--primary-dark), #00695c) !important; }

        .section-title h2 i { color: var(--primary-dark) !important; }
        .view-all-btn { background: linear-gradient(135deg, var(--primary-dark), #00695c) !important; }
        .view-all-btn:hover { box-shadow: 0 6px 20px rgba(0, 77, 64, 0.3) !important; }

        .order-card:hover { border-color: var(--primary-dark) !important; }
        .order-number { color: var(--primary-dark) !important; }
        
        .solar-account-tab.active {
            background-color: var(--primary-dark) !important;
            color: #fff !important;
        }
        
        .solar-account-user-avatar {
            border-color: var(--primary-dark) !important;
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
            <a href="/">
            <img src="{{ url('homepage/images/ologo.png') }}" alt="Logo" class="logo" />
</a>
            <!-- Hamburger toggle (mobile only) -->
            <button class="menu-toggle" aria-label="Toggle Menu">
                <span class="hamburger-line top"></span>
                <span class="hamburger-line middle"></span>
                <span class="hamburger-line bottom"></span>
            </button>

            <nav class="nav-links">
                <a href="/">Home</a>
                <a href="/user/dashboard">Dashboard</a>
                <a href="/user/profile">Profile</a>
                <a href="/user/orders">Orders</a>
                <a href="/user/change-password">Change Password</a>
               
            </nav>

            <div class="actions">
                @if(Auth::check())
                <form action="/logout" method="POST" style="display: inline;">
                    @csrf
                <a>
                    <div class="welcome">Welcome To Best4UArena</div>
                    <button class="submit" style=" background-color: #dc3545; /* red */
                            color: #fff;
                            border: none;
                            padding: 10px 18px;
                            border-radius: 6px;
                            font-size: 15px;
                            cursor: pointer;
                            transition: background-color 0.3s ease, transform 0.1s ease;">
                            Logout</button>
                </a>
                </form>
                <a href="/cart" class="cart">
                    <div class="cart-icon">
                        <img src="{{ url('homepage/images/cart.svg') }}" width="20" alt="cart" />
                        <!-- <span></span> -->
                    </div>
                    <div>
                        <div class="small-cart">Cart</div>
                    </div>
                </a>
                @else  
                <a href="/login">
                    <div class="welcome">Welcome To Best4UArena</div>
                    <div class="login">LOG IN / REGISTER</div>
                </a>
                @endif
            </div>
        </div>

      
         <div class="nav">
            <form action="{{ route('search') }}" method="GET" class="div-search-cat">
                <div class="input">
                    <input class="div-placeholder" name="q" placeholder="Search anything..." type="text" value="{{ request('q') }}" />
                </div>
                <div class="options">
                    <button type="submit" class="frame" style="background: none; border: none; cursor: pointer;">
                        <div class="text-wrapper">Search</div>
                    </button>
                </div>
            </form>

            <div class="shipping-info">
                <div class="link-free-shipping">FREE SHIPPING OVER ₦1,000,000</div>
                <div class="link-days-money">30 DAYS MONEY BACK</div>
            </div>
          
        </div>
    </header>

    <main>
        <!-- Breadcrumb -->
        @yield('breadcrumb')

        <!-- Solar Account Container -->
        <div class="solar-account">
            <!-- Sidebar -->
            <div class="solar-account-sidebar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="solar-account-user">
                    <div class="solar-account-user-avatar">
                        <img src="{{ asset('homepage/images/avatar.svg') }}" alt="User Avatar">
                    </div>
                    <div class="solar-account-user-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="solar-account-tabs">
                    <a href="{{ route('user.dashboard') }}" class="solar-account-tab {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('user.profile') }}" class="solar-account-tab {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('user.orders.index') }}" class="solar-account-tab {{ request()->routeIs('user.orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <a href="{{ route('user.change-password') }}" class="solar-account-tab {{ request()->routeIs('user.change-password') ? 'active' : '' }}">
                        <i class="fas fa-lock"></i> Change Password
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                        @csrf
                        <button type="submit" class="solar-account-tab logout-btn" style="width: 100%; text-align: left; border: none; background: none; color: inherit; font: inherit; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="solar-account-content">
                @yield('content')
            </div>
        </div>
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
            <p>© 2025 Best4UArena. All Rights Reserved</p>
            <div class="payment-methods">
                <span><img src="{{ url('homepage/images/paypal.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/visa.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/mastercard.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/paypal.svg') }}" alt="x"/></span>
                <span><img src="{{ url('homepage/images/klarna.svg') }}" alt="x"/></span>
            </div>
        </div>
    </footer>


    <script>
        // Toggle menu for mobile
        document.querySelector(".menu-toggle").addEventListener("click", function() {
            this.classList.toggle("active");
            document.querySelector(".nav-links").classList.toggle("active");
            document.querySelector(".actions").classList.toggle("active");
        });
    </script>
    @yield('script')
</body>

</html>