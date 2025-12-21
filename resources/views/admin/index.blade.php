@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('css/admin-mobile.css') }}">
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
    
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Admin</p>
    </div>
</div>
@endsection


@section('content')
<div class="dashboard">
    <header class="dashboard-header" style="display:block">
        <h1>Dashboard</h1>
        <div class="dashboard-stats">
            <a href="{{ route('admin.orders') }}" class="stat-card stat-card-clickable orders-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Orders</h3>
                    <p>{{ $totalOrders }}</p>
                </div>
                <div class="stat-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <a href="{{ route('admin.products') }}" class="stat-card stat-card-clickable products-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Products</h3>
                    <p>{{ $totalProducts }}</p>
                </div>
                <div class="stat-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <a href="{{ route('admin.users') }}" class="stat-card stat-card-clickable users-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Users</h3>
                    <p>{{ $totalUsers }}</p>
                </div>
                <div class="stat-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            <div class="stat-card revenue-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Revenue</h3>
                    <p>₦{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="stat-badge">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </header>
    <br>
    <!-- Dashboard Content Grid -->
    <div class="dashboard-content-grid">
        <!-- Recent Orders Section -->
        <section class="dashboard-section recent-orders-section">
            <div class="section-header">
                <h2 style="color:white"> Recent Orders</h2>
                <a href="{{ route('admin.orders') }}" class="view-all-btn">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="section-content">
                <!-- Desktop Table View -->
                <div class="table-wrapper desktop-view">
                    <div class="table">
                        <div class="table-header">
                            <span>Customer</span>
                            <span>Order #</span>
                            <span>Date</span>
                            <span>Amount</span>
                            <span>Status</span>
                        </div>
                        @forelse($recentOrders as $order)
                        <div class="table-row">
                            <span class="customer-info">
                                <strong>{{ $order->user->name ?? $order->name }}</strong>
                            </span>
                            <span class="order-number">#{{ $order->order_number }}</span>
                            <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                            <span class="order-amount">₦{{ number_format($order->total_amount, 2) }}</span>
                            <span class="order-status">
                                <span class="badge {{ $order->status === 'completed' ? 'paid' : 'pending' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </span>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No recent orders found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="mobile-cards mobile-view">
                    @forelse($recentOrders as $order)
                    <div class="dashboard-card order-card">
                        <div class="card-header">
                            <div class="order-info">
                                <h4 class="order-number">#{{ $order->order_number }}</h4>
                                <span class="badge {{ $order->status === 'completed' ? 'paid' : 'pending' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="order-date">{{ $order->created_at->format('M d, Y') }}</div>
                        </div>
                        
                        <div class="card-body">
                            <div class="card-row">
                                <span class="card-label">Customer:</span>
                                <span class="card-value">{{ $order->user->name ?? $order->name }}</span>
                            </div>
                            <div class="card-row">
                                <span class="card-label">Amount:</span>
                                <span class="card-value order-amount">₦{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No recent orders found</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Top Products Section -->
        <section class="dashboard-section top-products-section">
            <div class="section-header">
                <h2 style="color:#fff"><i class="fas fa-star"></i> Top Products</h2>
                <a href="/admin/products" class="view-all-btn">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="section-content">
                <!-- Desktop Table View -->
                <div class="table-wrapper desktop-view">
                    <div class="table">
                        <div class="table-header">
                            <span>Product Name</span>
                            <span>Price</span>
                            <span>Category</span>
                            <span>Orders</span>
                        </div>
                        @forelse($topProducts as $product)
                        <div class="table-row">
                            <span class="product-name">
                                <strong>{{ $product->name }}</strong>
                            </span>
                            <span class="product-price">₦{{ number_format($product->price, 2) }}</span>
                            <span class="product-category">{{ $product->category->name ?? 'N/A' }}</span>
                            <span class="product-orders">
                                <span class="orders-count">{{ $product->order_items_count }}</span>
                            </span>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-box"></i>
                            <p>No products found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="mobile-cards mobile-view">
                    @forelse($topProducts as $product)
                    <div class="dashboard-card product-card">
                        <div class="card-header">
                            <div class="product-info">
                                <h4 class="product-name">{{ $product->name }}</h4>
                                <span class="orders-badge">{{ $product->order_items_count }} orders</span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="card-row">
                                <span class="card-label">Price:</span>
                                <span class="card-value product-price">₦{{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="card-row">
                                <span class="card-label">Category:</span>
                                <span class="card-value">{{ $product->category->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-box"></i>
                        <p>No products found</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('script')

@endsection