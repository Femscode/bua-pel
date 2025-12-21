@extends('user.master')

@section('breadcrumb')
<div class="breadcrumb">
    <div class="container">
        <nav>
            <a href="{{ route('home') }}">Home</a> > 
            <span>Dashboard</span>
        </nav>
    </div>
</div>
@endsection

@section('content')
<div class="dashboard">
    <!-- Welcome Header -->
    <div class="dashboard-welcome">
        <div class="welcome-content">
            <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="welcome-subtitle">Here's an overview of your account activity</p>
        </div>
        <div class="welcome-avatar">
            <div class="avatar-circle">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Enhanced Stats Cards -->
    <div class="dashboard-stats">
        <div class="stat-card total-orders">
            <div class="stat-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-content">
                <h3>Total Orders</h3>
                <p class="stat-number">{{ $totalOrders }}</p>
                <span class="stat-label">All time orders</span>
            </div>
        </div>
        
        <div class="stat-card pending-orders">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>Pending Orders</h3>
                <p class="stat-number">{{ $pendingOrders }}</p>
                <span class="stat-label">Awaiting processing</span>
            </div>
        </div>
        
        <div class="stat-card completed-orders">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>Completed Orders</h3>
                <p class="stat-number">{{ $completedOrders }}</p>
                <span class="stat-label">Successfully delivered</span>
            </div>
        </div>
        
        <div class="stat-card total-spent">
            <div class="stat-icon">
                <i class="fas fa-naira-sign"></i>
            </div>
            <div class="stat-content">
                <h3>Total Spent</h3>
                <p class="stat-number">₦{{ number_format($totalSpent, 2) }}</p>
                <span class="stat-label">Lifetime spending</span>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders Section -->
    <section class="orders-section">
        <div class="section-header">
            <div class="section-title">
                <h2><i class="fas fa-history"></i> Recent Orders</h2>
                <p class="section-subtitle">Your latest order activity</p>
            </div>
            @if($recentOrders->count() > 0)
                <a href="/user/orders" class="view-all-btn">
                    <i class="fas fa-arrow-right"></i> View All Orders
                </a>
            @endif
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="orders-grid">
                @foreach($recentOrders as $order)
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-info">
                            <h4 class="order-number">#{{ $order->order_number ?? $order->id }}</h4>
                            <p class="order-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $order->created_at->format('M d, Y') }}
                            </p>
                            @if($order->orderItems && $order->orderItems->count() > 0)
                                <p class="order-items-count">
                                    <i class="fas fa-box"></i>
                                    {{ $order->orderItems->count() }} item(s)
                                </p>
                            @endif
                        </div>
                        <div class="order-status">
                            <span class="status-badge {{ strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered' ? 'completed' : 'pending' }}">
                                @if(strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered')
                                    <i class="fas fa-check-circle"></i>
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-card-body">
                        <div class="order-total">
                            <span class="total-label">Total Amount</span>
                            <span class="total-amount">₦{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="order-card-footer">
                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if(in_array(strtolower($order->status), ['delivered', 'completed']))
                            <button class="btn btn-secondary" onclick="alert('Reorder functionality coming soon!')">
                                <i class="fas fa-redo"></i> Reorder
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Start Shopping
                </a>
            </div>
        @endif
    </section>
</div>
@endsection