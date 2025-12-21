@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/admin-master.css') }}" />
<link rel="stylesheet" href="{{ url('homepage/css/admin-dashboard.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ url('css/admin-mobile.css') }}">

@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
   
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Orders</p>
    </div>
</div>
@endsection

@section('content')
    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Orders Management</h1>
            <div class="header-actions">
                <form method="POST" action="{{ route('admin.orders.export') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2"/>
                            <polyline points="7,10 12,15 17,10" stroke="currentColor" stroke-width="2"/>
                            <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Export to Excel
                    </button>
                </form>
            </div>
        </header>

        <section class="transactions">
            <div class="orders-filter">
                <div class="filter-group">
                    <label for="status-filter">Filter by Status:</label>
                    <select id="status-filter" onchange="filterOrders()">
                        <option value="">All Orders</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search-orders">Search Orders:</label>
                    <input type="text" id="search-orders" placeholder="Search by order number, customer name..." onkeyup="searchOrders()">
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table" id="orders-table">
                    <div class="table-header">
                        <span>Order #</span>
                        <span>Customer</span>
                        <span>Date</span>
                        <span>Amount</span>
                        <span>Status</span>
                        <span>Actions</span>
                    </div>
                    @forelse($orders as $order)
                    <div class="table-row" data-status="{{ strtolower($order->status) }}" data-search="{{ strtolower($order->order_number ?? 'ORD-' . $order->id) }} {{ strtolower($order->name) }} {{ strtolower($order->email) }}">
                        <span class="order-number">
                            <strong>#{{ $order->order_number ?? 'ORD-' . $order->id }}</strong>
                            <small>{{ $order->orderItems->count() }} item(s)</small>
                        </span>
                        <span class="customer-info">
                            <strong>{{ $order->name }}</strong>
                            <small>{{ $order->email }}</small>
                        </span>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                        <span class="amount">₦{{ number_format($order->total_amount, 2) }}</span>
                        <span>
                            <span class="badge {{ strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered' ? 'paid' : 'pending' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </span>
                        <span class="actions">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                        </span>
                    </div>
                    @empty
                    <div class="no-orders">
                        <p>No orders found.</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Mobile Cards Layout -->
                <div class="mobile-cards">
                    @forelse($orders as $order)
                    <div class="mobile-card order-mobile-card" data-status="{{ strtolower($order->status) }}" data-search="{{ strtolower($order->order_number ?? 'ORD-' . $order->id) }} {{ strtolower($order->name) }} {{ strtolower($order->email) }}">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title order-number">#{{ $order->order_number ?? 'ORD-' . $order->id }}</h4>
                                <p class="card-subtitle order-items-count">{{ $order->orderItems->count() }} item(s)</p>
                            </div>
                            <span class="badge {{ strtolower($order->status) === 'completed' || strtolower($order->status) === 'delivered' ? 'paid' : 'pending' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">Customer:</span>
                            <span class="card-value">
                                <strong>{{ $order->name }}</strong><br>
                                <small>{{ $order->email }}</small>
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">Date:</span>
                            <span class="card-value">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">Amount:</span>
                            <span class="card-value order-amount">₦{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        <div class="card-actions">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View Details</a>
                        </div>
                    </div>
                    @empty
                    <div class="no-orders">
                        <p>No orders found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </section>
    </div>

    <style>
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-success {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .btn-success:hover {
        background: #218838;
    }

    .orders-filter {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-weight: 500;
        color: #333;
        font-size: 14px;
    }

    .filter-group select,
    .filter-group input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        min-width: 200px;
    }

    .table-header {
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 1fr;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        font-weight: bold;
        border-radius: 8px 8px 0 0;
    }

    .table-row {
        display: grid;
        grid-template-columns: 1.5fr 1.5fr 1fr 1fr 1fr 1fr;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #eee;
        align-items: center;
        transition: background-color 0.2s;
    }

    .table-row:hover {
        background-color: #f8f9fa;
    }

    .order-number strong {
        display: block;
        color: #003177;
    }

    .order-number small,
    .customer-info small {
        color: #6c757d;
        font-size: 12px;
    }

    .customer-info strong {
        display: block;
        color: #333;
    }

    .amount {
        font-weight: 600;
        color: #28a745;
    }

    .actions {
        display: flex;
        gap: 5px;
    }

    .no-orders {
        text-align: center;
        padding: 40px;
        color: #6c757d;
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .orders-filter {
            flex-direction: column;
            gap: 15px;
        }

        .filter-group select,
        .filter-group input {
            min-width: 100%;
        }

        .table-header,
        .table-row {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .table-header span,
        .table-row span {
            padding: 5px 0;
        }

        .table-header span:not(:first-child),
        .table-row span:not(:first-child) {
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .table-header span::before {
            content: attr(data-label) ": ";
            font-weight: bold;
            color: #003177;
        }
    }
    </style>

    <script>
    function filterOrders() {
        const statusFilter = document.getElementById('status-filter').value.toLowerCase();
        const rows = document.querySelectorAll('.table-row');
        const cards = document.querySelectorAll('.mobile-card');
        
        // Filter table rows
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (statusFilter === '' || status === statusFilter) {
                row.style.display = 'grid';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Filter mobile cards
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (statusFilter === '' || status === statusFilter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function searchOrders() {
        const searchTerm = document.getElementById('search-orders').value.toLowerCase();
        const rows = document.querySelectorAll('.table-row');
        const cards = document.querySelectorAll('.mobile-card');
        
        // Search table rows
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = 'grid';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Search mobile cards
        cards.forEach(card => {
            const searchData = card.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Add data labels for mobile responsive
    document.addEventListener('DOMContentLoaded', function() {
        const headers = ['Order #', 'Customer', 'Date', 'Amount', 'Status', 'Actions'];
        const rows = document.querySelectorAll('.table-row');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('span');
            cells.forEach((cell, index) => {
                cell.setAttribute('data-label', headers[index]);
            });
        });
    });
    </script>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tab switching functionality
        const tabs = document.querySelectorAll('.solar-account-tab');
        const contents = document.querySelectorAll('.solar-account-details-content');
        const sidebar = document.querySelector('.solar-account-sidebar');
        const toggleButton = document.querySelector('.solar-account-sidebar-toggle');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('solar-account-tab-active'));
                tab.classList.add('solar-account-tab-active');

                contents.forEach(content => {
                    content.style.opacity = '0';
                    content.style.display = 'none';
                });

                const targetContent = document.getElementById(tab.dataset.tab);
                setTimeout(() => {
                    targetContent.style.display = 'block';
                    targetContent.style.opacity = '1';
                }, 200);

                // Collapse sidebar on mobile after tab selection
                if (window.innerWidth <= 767) {
                    sidebar.classList.remove('solar-account-sidebar-open');
                }
            });
        });

        // Sidebar toggle for mobile
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('solar-account-sidebar-open');
        });

        // Form submission handling
        const accountForm = document.getElementById('account-form');
        const passwordForm = document.getElementById('password-form');

        accountForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(accountForm);
            const data = Object.fromEntries(formData);
            console.log('Account Info Submitted:', data);
            alert('Account information saved! (Demo submission)');
        });

        passwordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(passwordForm);
            const data = Object.fromEntries(formData);
            if (data.new_password !== data.confirm_password) {
                alert('New password and confirmation do not match!');
                return;
            }
            console.log('Password Change Submitted:', data);
            alert('Password changed successfully! (Demo submission)');
        });
    });
</script>
@endsection