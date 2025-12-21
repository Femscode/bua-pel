@extends('admin.master')
@section('header')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endsection

<link rel="stylesheet" href="{{ url('css/admin-products.css') }}">
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
        <p class="solar-breadcrumb-current">Products</p>
    </div>
</div>
@endsection

@section('content')

    <div class="dashboard">
        <header class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">All Products</h1>
            <div class="header-actions" style="display: flex; gap: 10px; align-items: center;">
                <!-- Import Button -->
                <button id="bulkDeleteBtn" class="btn" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; display: none; align-items: center; gap: 5px;">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                <button onclick="document.getElementById('importModal').style.display='block'" class="btn" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-file-import"></i> Import
                </button>
                
                <!-- Export Form -->
                <form action="{{ route('admin.products.export') }}" method="POST" style="margin: 0;">
                    @csrf
                    <select name="format" onchange="if(this.value){this.form.submit(); this.value='';}" style="padding: 10px; border-radius: 5px; border: 1px solid #ddd; cursor: pointer; height: 40px;">
                        <option value="" disabled selected>Export As...</option>
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </form>

                <a href="{{ route('admin.add-product') }}" class="btn btn-primary" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-flex; align-items: center; height: 40px; box-sizing: border-box;">Add New Product</a>
            </div>
        </header>

        <!-- Import Modal -->
        <div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
            <div style="background: white; width: 500px; margin: 100px auto; padding: 20px; border-radius: 8px; position: relative; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <span onclick="document.getElementById('importModal').style.display='none'" style="position: absolute; right: 20px; top: 15px; cursor: pointer; font-size: 24px; color: #666;">&times;</span>
                <h2 style="margin-top: 0;">Import Products</h2>
                <p style="color: #666; margin-bottom: 20px;">Upload a CSV file to import products. (Save Excel as CSV)</p>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #e9ecef;">
                    <p style="margin: 0 0 10px 0; font-weight: bold;">Need a template?</p>
                    <a href="{{ route('admin.products.template') }}" style="color: #007bff; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                        <i class="fas fa-download"></i> Download CSV Template
                    </a>
                </div>

                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500;">Select File (CSV)</label>
                        <input type="file" name="file" accept=".csv" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <div style="text-align: right; border-top: 1px solid #eee; padding-top: 20px;">
                        <button type="button" onclick="document.getElementById('importModal').style.display='none'" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer;">Cancel</button>
                        <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Import Products</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- <section class="transactions"> -->
            <div class="admin-table-container">
                <table id="admin-products-table" class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                            <th>Image</th>
                            <th>Name</th>
                           
                            <th>Price (Qty)</th>
                          
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                            <td class="image-cell">
                                @if($product->image)
                                    @php
                                        $images = json_decode($product->image, true);
                                        $firstImage = is_array($images) ? $images[0] : $product->image;
                                    @endphp
                                    <img src="{{ url('uploads/products/' . $firstImage) }}" alt="{{ $product->name }}" class="admin-table-image">
                                @else
                                <div class="admin-no-image">
                                    <i class="fas fa-box"></i>
                                </div>
                                @endif
                            </td>
                            <td>{{ $product->name }} <br>{{ $product->category->name ?? 'N/A' }}</td>
                            <td>₦{{ number_format($product->price, 2) }}<br> <span class="admin-badge {{ $product->is_active ? 'admin-badge-success' : 'admin-badge-secondary' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }} ({{ $product->stock_quantity ?? 0 }})
                                </span></td>
                            
                           
                            <td class="actions-cell">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.duplicate', $product->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-secondary" title="Duplicate Product">
                                        <i class="fas fa-copy"></i> Duplicate
                                    </button>
                                </form>
                                <button data-product-id="{{ $product->id }}" class="admin-btn admin-btn-sm admin-btn-danger delete-product-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination removed; DataTables handles client-side pagination -->
        <!-- </section> -->
      
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load DataTables
        // Ensure jQuery and DataTables are available
        const loadScript = (src) => new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = reject;
            document.head.appendChild(s);
        });

        const initDataTable = () => {
            if (window.jQuery && jQuery.fn.dataTable) {
                const tableEl = jQuery('#admin-products-table');
                if (tableEl.length) {
                    tableEl.DataTable({
                        order: [[1, 'asc']], // Sort by Name column
                        columnDefs: [
                            { targets: [0, 3], orderable: false } // Image and Actions not sortable
                        ],
                        pageLength: 10
                    });
                    // Hide Laravel pagination below the table (optional)
                    const nextEl = document.querySelector('.admin-table-container')?.nextElementSibling;
                    if (nextEl) nextEl.style.display = 'none';
                }
            }
        };

        // Load jQuery then DataTables, then init
        (async () => {
            try {
                if (!window.jQuery) {
                    await loadScript('https://code.jquery.com/jquery-3.6.0.min.js');
                }
                await loadScript('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js');
                initDataTable();
            } catch (e) {
                console.error('Failed to load DataTables:', e);
            }
        })();

        // Show success/error messages
        var successMessage = document.querySelector('meta[name="success-message"]');
        var errorMessage = document.querySelector('meta[name="error-message"]');
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        if (successMessage) {
            Toast.fire({
                icon: 'success',
                title: successMessage.getAttribute('content')
            });
        }
        
        if (errorMessage) {
            Toast.fire({
                icon: 'error',
                title: errorMessage.getAttribute('content')
            });
        }

        // Add event listeners to delete buttons
        var deleteButtons = document.querySelectorAll('.delete-product-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var productId = this.getAttribute('data-product-id');
                deleteProduct(productId);
            });
        });
        
        // Tab switching functionality
        var tabs = document.querySelectorAll('.solar-account-tab');
        var contents = document.querySelectorAll('.solar-account-details-content');
        var sidebar = document.querySelector('.solar-account-sidebar');
        var toggleButton = document.querySelector('.solar-account-sidebar-toggle');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                tabs.forEach(function(t) { t.classList.remove('solar-account-tab-active'); });
                tab.classList.add('solar-account-tab-active');

                contents.forEach(function(content) {
                    content.style.opacity = '0';
                    content.style.display = 'none';
                });

                var targetContent = document.getElementById(tab.dataset.tab);
                if (targetContent) {
                    setTimeout(function() {
                        targetContent.style.display = 'block';
                        targetContent.style.opacity = '1';
                    }, 200);
                }

                // Collapse sidebar on mobile after tab selection
                if (window.innerWidth <= 767) {
                    sidebar.classList.remove('solar-account-sidebar-open');
                }
            });
        });

        // Sidebar toggle for mobile
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('solar-account-sidebar-open');
            });
        }

        // Form submission handling
        var accountForm = document.getElementById('account-form');
        var passwordForm = document.getElementById('password-form');

        if (accountForm) {
            accountForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(accountForm);
                var data = Object.fromEntries(formData);
                console.log('Account Info Submitted:', data);
                alert('Account information saved! (Demo submission)');
            });
        }

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(passwordForm);
                var data = Object.fromEntries(formData);
                if (data.new_password !== data.confirm_password) {
                    alert('New password and confirmation do not match!');
                    return;
                }
                console.log('Password Change Submitted:', data);
                alert('Password changed successfully! (Demo submission)');
            });
        }
    });

    // Delete product function
    function deleteProduct(productId) {
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
                form.action = '/admin/products/' + productId;
                
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

    // Bulk Delete Functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkDeleteBtn.style.display = 'flex';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkDeleteButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkDeleteButton);
    });

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.product-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${selectedIds.length} products. This cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.products.bulk_delete") }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    }
</script>
@endsection
