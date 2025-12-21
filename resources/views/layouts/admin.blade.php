<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Panel') - Oplug Energies</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Admin Styles -->
    <link rel="stylesheet" href="{{ url('homepage/css/admin-master.css') }}" />
    <link rel="stylesheet" href="{{ url('homepage/css/admin-dashboard.css') }}" />
    
    @yield('head')
    @stack('styles')
    
    @push('styles')
    <style>
    /* Admin Layout Styles */
    .admin-layout {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
    }

    .admin-sidebar {
        width: 250px;
        background: #2c3e50;
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        transition: transform 0.3s ease;
        z-index: 1000;
    }

    .admin-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #34495e;
        text-align: center;
    }

    .admin-sidebar-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .admin-sidebar-nav {
        padding: 20px 0;
    }

    .admin-nav-item {
        display: block;
        padding: 12px 20px;
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .admin-nav-item:hover,
    .admin-nav-item.active {
        background: #34495e;
        color: white;
        border-left-color: #3498db;
        text-decoration: none;
    }

    .admin-nav-item i {
        margin-right: 10px;
        width: 16px;
    }

    .admin-main {
        flex: 1;
        margin-left: 250px;
        display: flex;
        flex-direction: column;
    }

    .admin-header {
        background: white;
        padding: 15px 30px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .admin-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6c757d;
    }

    .admin-breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }

    .admin-breadcrumb a:hover {
        text-decoration: underline;
    }

    .admin-breadcrumb .separator {
        color: #6c757d;
    }

    .admin-breadcrumb .current {
        color: #495057;
        font-weight: 500;
    }

    .admin-user-menu {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-content {
        flex: 1;
        padding: 30px;
    }

    /* Mobile Sidebar Toggle */
    .admin-mobile-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        color: #495057;
        cursor: pointer;
    }

    /* Form Styles */
    .admin-form-container {
        padding: 30px;
    }

    .admin-form {
        max-width: 800px;
    }

    .admin-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .admin-form-group {
        display: flex;
        flex-direction: column;
    }

    .admin-form-group-full {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .admin-form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #495057;
        font-size: 14px;
    }

    .admin-form-label .required {
        color: #dc3545;
    }

    .admin-form-input,
    .admin-form-select,
    .admin-form-textarea {
        padding: 10px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .admin-form-input:focus,
    .admin-form-select:focus,
    .admin-form-textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    .admin-form-input.error,
    .admin-form-select.error,
    .admin-form-textarea.error {
        border-color: #dc3545;
    }

    .admin-form-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .admin-form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* File Upload Styles */
    .admin-file-upload {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .admin-file-drop-zone {
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .admin-file-drop-zone:hover {
        border-color: #007bff;
        background: #f8f9ff;
    }

    .admin-file-drop-zone.dragover {
        border-color: #007bff;
        background: #e3f2fd;
    }

    .admin-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .admin-file-drop-content i {
        font-size: 32px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .admin-file-drop-content p {
        margin: 0 0 5px 0;
        color: #495057;
    }

    .admin-file-browse {
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
    }

    .admin-file-drop-content small {
        color: #6c757d;
        font-size: 12px;
    }

    /* Image Preview Styles */
    .admin-image-preview {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .admin-preview-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .admin-current-image {
        position: relative;
        display: inline-block;
    }

    .admin-current-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .admin-current-image-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px;
        border-radius: 0 0 8px 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-current-image-label {
        font-size: 12px;
        font-weight: 500;
    }

    /* Alert Styles */
    .admin-alert {
        padding: 12px 16px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .admin-alert-info {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
    }

    .admin-alert i {
        font-size: 16px;
    }

    /* Form Actions */
    .admin-form-actions {
        display: flex;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        margin-top: 30px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }
        
        .admin-sidebar.open {
            transform: translateX(0);
        }
        
        .admin-main {
            margin-left: 0;
        }
        
        .admin-mobile-toggle {
            display: block;
        }
        
        .admin-form-grid {
            grid-template-columns: 1fr;
        }
        
        .admin-content {
            padding: 20px;
        }
        
        .admin-form-container {
            padding: 20px;
        }
        
        .admin-form-actions {
            flex-direction: column;
        }
    }
    </style>
    @endpush
</head>
<body>
    <!-- Breadcrumb -->
    @hasSection('breadcrumb')
        <div class="solar-breadcrumb">
            @yield('breadcrumb')
        </div>
    @endif

    <!-- Main Admin Layout -->
    <div class="solar-account">
        <!-- Sidebar -->
        <div class="solar-account-sidebar">
            <button class="solar-account-sidebar-toggle">
                <i class="fas fa-bars"></i> Menu
            </button>
            
            <div class="solar-account-user">
                <div class="solar-account-user-name">Admin Panel</div>
                <div class="solar-account-user-email">{{ Auth::user()->email }}</div>
            </div>
            
            <div class="solar-account-tabs">
                <a href="{{ route('admin.dashboard') }}" class="solar-account-tab {{ request()->routeIs('admin.dashboard') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <a href="{{ route('admin.orders') }}" class="solar-account-tab {{ request()->routeIs('admin.orders*') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <a href="{{ route('admin.products') }}" class="solar-account-tab {{ request()->routeIs('admin.products*') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-box"></i> Products
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="solar-account-tab {{ request()->routeIs('admin.categories*') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-tags"></i> Categories
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="solar-account-tab {{ request()->routeIs('admin.settings*') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-cog"></i> Settings
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <a href="{{ route('admin.change-password') }}" class="solar-account-tab {{ request()->routeIs('admin.change-password*') ? 'solar-account-tab-active' : '' }}">
                    <div class="solar-account-tab-text">
                        <i class="fas fa-key"></i> Change Password
                    </div>
                    <div class="solar-account-tab-icon">→</div>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="solar-account-tab" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                        <div class="solar-account-tab-text">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </div>
                        <div class="solar-account-tab-icon">→</div>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="solar-account-details">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.querySelector('.solar-account-sidebar-toggle');
            const sidebar = document.querySelector('.solar-account-sidebar');
            
            if (toggle && sidebar) {
                toggle.addEventListener('click', function() {
                    sidebar.classList.toggle('solar-account-sidebar-open');
                });
            }
        });
    </script>
    
    @yield('script')
    @stack('scripts')
</body>
</html>