@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/admin-master.css') }}" />
<link rel="stylesheet" href="{{ url('homepage/css/admin-dashboard.css') }}" />
<link rel="stylesheet" href="{{ url('css/admin-products.css') }}">
<link rel="stylesheet" href="{{ url('css/admin-mobile.css') }}">
@endsection

@if(session('success'))
<meta name="success-message" content="{{ session('success') }}">
@endif
@if(session('error'))
<meta name="error-message" content="{{ session('error') }}">
@endif

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
   
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Users</p>
    </div>
</div>
@endsection

@section('content')

    <div class="dashboard">
        <header class="dashboard-header">
            <h1>Users Management</h1>
            <div class="header-actions">
                <form method="POST" action="{{ route('admin.users.export') }}" style="display: inline;">
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
                    <label for="role-filter">Filter by Role:</label>
                    <select id="role-filter" onchange="filterUsers()">
                        <option value="">All Users</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="search-users">Search Users:</label>
                    <input type="text" id="search-users" placeholder="Search by name, email..." onkeyup="searchUsers()">
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table" id="users-table">
                    <div class="table-header">
                        <span>Name</span>
                        <span>Contact Info</span>
                        <span>Total Orders</span>
                        <span>Role</span>
                         @if(Auth::user()->email == 'fasanyafemi@gmail.com' || Auth::user()->email == 'info@best4uarena.com')
                       
                        <span>Actions</span>
                        @endif
                    </div>
                    @forelse($users as $user)
                    <div class="table-row" data-role="{{ strtolower($user->role) }}" data-search="{{ strtolower($user->name) }} {{ strtolower($user->email) }} {{ strtolower($user->phone ?? '') }}">
                        <span class="user-name">
                            <strong>{{ $user->name }}</strong>
                        </span>
                        <span class="user-info">
                            <strong>{{ $user->email }}</strong>
                            <small>{{ $user->phone }}</small>
                        </span>
                        <span>{{ $user->totalOrders() }}</span>
                        <span>
                            <span class="badge {{ strtolower($user->role) === 'admin' ? 'paid' : 'pending' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </span>
                        @if(Auth::user()->email == 'fasanyafemi@gmail.com' || Auth::user()->email == 'info@best4uarena.com')
                        <span class="actions">
                            @if($user->role == 'user')
                            <a href="{{ route('admin.make_admin', $user->id) }}" class="btn btn-sm btn-primary">Make Admin</a>
                            @else 
                            <a href="{{ route('admin.make_admin', $user->id) }}" class="btn btn-sm btn-warning">Remove Admin</a>
                            @endif
                            <button data-user-id="{{ $user->id }}" class="btn btn-sm btn-danger delete-user-btn">Delete</button>
                        </span>
                        @endif
                    </div>
                    @empty
                    <div class="no-users">
                        <p>No users found.</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Mobile Cards Layout -->
                <div class="mobile-cards">
                    @forelse($users as $user)
                    <div class="mobile-card user-mobile-card" data-role="{{ strtolower($user->role) }}" data-search="{{ strtolower($user->name) }} {{ strtolower($user->email) }} {{ strtolower($user->phone ?? '') }}">
                        <div class="card-header">
                            <div class="user-info-mobile">
                                <h4 class="card-title">{{ $user->name }}</h4>
                                <p class="user-email">{{ $user->email }}</p>
                                @if($user->phone)
                                <p class="user-phone">{{ $user->phone }}</p>
                                @endif
                            </div>
                            <span class="badge {{ strtolower($user->role) === 'admin' ? 'paid' : 'pending' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        
                        <div class="card-row">
                            <span class="card-label">Total Orders:</span>
                            <span class="card-value">{{ $user->totalOrders() }}</span>
                        </div>
                        
                        @if(Auth::user()->email == 'fasanyafemi@gmail.com' || Auth::user()->email == 'info@best4uarena.com')
                        <div class="card-actions">
                            @if($user->role == 'user')
                            <a href="{{ route('admin.make_admin', $user->id) }}" class="btn btn-sm btn-primary">Make Admin</a>
                            @else 
                            <a href="{{ route('admin.make_admin', $user->id) }}" class="btn btn-sm btn-warning">Remove Admin</a>
                            @endif
                            <button data-user-id="{{ $user->id }}" class="btn btn-sm btn-danger delete-user-btn">Delete</button>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="no-users">
                        <p>No users found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
            @endif
        </section>
      
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success/error messages
        var successMessage = document.querySelector('meta[name="success-message"]');
        var errorMessage = document.querySelector('meta[name="error-message"]');
        
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage.getAttribute('content'),
                timer: 3000,
                showConfirmButton: false
            });
        }
        
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage.getAttribute('content'),
                timer: 3000,
                showConfirmButton: false
            });
        }

        // Add event listeners to delete buttons
        var deleteButtons = document.querySelectorAll('.delete-user-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var userId = this.getAttribute('data-user-id');
                deleteUser(userId);
            });
        });
        
        // Add data labels for mobile responsive
        const headers = ['Name', 'Contact Info', 'Total Orders', 'Role', 'Actions'];
        const rows = document.querySelectorAll('.table-row');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('span');
            cells.forEach((cell, index) => {
                cell.setAttribute('data-label', headers[index]);
            });
        });
    });

    // Filter users by role
    function filterUsers() {
        const roleFilter = document.getElementById('role-filter').value.toLowerCase();
        const tableRows = document.querySelectorAll('.table-row');
        const mobileCards = document.querySelectorAll('.mobile-card');
        
        // Filter table rows
        tableRows.forEach(row => {
            const role = row.getAttribute('data-role');
            if (roleFilter === '' || role === roleFilter) {
                row.style.display = 'grid';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Filter mobile cards
        mobileCards.forEach(card => {
            const role = card.getAttribute('data-role');
            if (roleFilter === '' || role === roleFilter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Search users
    function searchUsers() {
        const searchTerm = document.getElementById('search-users').value.toLowerCase();
        const tableRows = document.querySelectorAll('.table-row');
        const mobileCards = document.querySelectorAll('.mobile-card');
        
        // Search table rows
        tableRows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = 'grid';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Search mobile cards
        mobileCards.forEach(card => {
            const searchData = card.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Delete user function
    function deleteUser(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
            if (result.isConfirmed) {
                // Create a form and submit it
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/users/' + userId;
                
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

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
        grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        font-weight: bold;
        border-radius: 8px 8px 0 0;
    }

    .table-row {
        display: grid;
        grid-template-columns: 1fr 1.5fr 1fr 1fr 1fr;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #eee;
        align-items: center;
        transition: background-color 0.2s;
    }

    .table-row:hover {
        background-color: #f8f9fa;
    }

    .user-name strong {
        display: block;
        color: #003177;
    }

    .user-info strong {
        display: block;
        color: #333;
    }

    .user-info small {
        color: #6c757d;
        font-size: 12px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge.paid {
        background-color: #d4edda;
        color: #155724;
    }

    .badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .actions {
        display: flex;
        gap: 5px;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .no-users {
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
@endsection