@extends('admin.master')

@section('header')
<link rel="stylesheet" href="{{ url('/css/admin-categories.css') }}">
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
   
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Categories</p>  
    </div>
</div>
@endsection

@section('content')
<div class="dashboard">
    <header class="dashboard-header">
        <h1 class="admin-page-title">Categories Management</h1>
        <div class="admin-page-actions">
            <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </header>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                   
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->products()->count() }}</td>
                    <td class="actions-cell">
                        <a href="{{ route('admin.products', ['category' => $category->id]) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                            <i class="fas fa-box"></i> View Products
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button data-category-id="{{ $category->id }}" class="admin-btn admin-btn-sm admin-btn-danger delete-category-btn">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-message">
                        <i class="fas fa-folder-open"></i>
                        <p>No categories found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="admin-pagination">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection



@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show validation errors with SweetAlert
        const successMessage = '{{ session("success") }}';
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage,
                timer: 3000,
                showConfirmButton: false
            });
        }

        const errorMessage = '{{ session("error") }}';
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                confirmButtonText: 'OK'
            });
        }

        // Handle delete category buttons
        document.querySelectorAll('.delete-category-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category-id');
                deleteCategory(categoryId);
            });
        });
    });

    function deleteCategory(categoryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/categories/' + categoryId;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
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
@endsection