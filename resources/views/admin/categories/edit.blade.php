@extends('admin.master')

@section('title', 'Edit Category')

@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/admin-categories.css') }}">
@endsection

@section('breadcrumb')
<div class="admin-breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <span class="separator">></span>
    <a href="{{ route('admin.categories.index') }}">Categories</a>
    <span class="separator">></span>
    <span class="current">Edit Category</span>
</div>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-page-header">
        <h1 class="admin-page-title">Edit Category</h1>
        <div class="admin-page-actions">
            <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Categories
            </a>
        </div>
    </div>
    <div class="admin-form-container">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="categoryForm" class="admin-form">
            @csrf
            @method('PUT')
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">Category Name <span class="required">*</span></label>
                    <input type="text" class="admin-form-input @error('name') error @enderror" 
                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="admin-form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                        Active Category
                    </label>
                    @error('is_active')
                        <div class="admin-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
          
            
            
            @if($category->products()->count() > 0)
            <div class="admin-form-group admin-form-group-full">
                <div class="admin-alert admin-alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> This category has {{ $category->products()->count() }} product(s) assigned to it.
                </div>
            </div>
            @endif
            
            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show validation errors with SweetAlert
    const errors = [];
    document.querySelectorAll('.admin-form-error').forEach(function(errorElement) {
        errors.push(errorElement.textContent);
    });
    
    if (errors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: errors.join('<br>'),
            confirmButtonText: 'OK'
        });
    }
    
    // Show success message
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
    
    // Show error message
    const errorMessage = '{{ session("error") }}';
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage,
            confirmButtonText: 'OK'
        });
    }
    
    // Image upload functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('category-image');
    const imagePreview = document.getElementById('file-preview');
    const previewImg = document.getElementById('previewImg');
    const removeBtn = document.getElementById('removeImage');
    const removeCurrentBtn = document.getElementById('removeCurrentImage');
    const browseTrigger = document.querySelector('.admin-file-browse');
    const uploadButton = document.getElementById('upload-button');

    // Browse button click
    if (browseTrigger) {
        browseTrigger.addEventListener('click', function() {
            fileInput.click();
        });
    }

    if (uploadButton) {
        uploadButton.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // Drop zone click
    if (dropZone) {
        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop events
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type.startsWith('image/')) {
                handleFileSelect(files[0]);
                // Create a new FileList with the dropped file
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                fileInput.files = dt.files;
            }
        });
    }

    // File input change
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
    }

    // Remove new image button
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            imagePreview.style.display = 'none';
        });
    }

    // Remove current image button
    if (removeCurrentBtn) {
        removeCurrentBtn.addEventListener('click', function() {
            const currentImageDiv = document.querySelector('.admin-current-image');
            if (currentImageDiv) {
                currentImageDiv.style.display = 'none';
                // Add a hidden input to indicate image removal
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_image';
                removeInput.value = '1';
                document.getElementById('categoryForm').appendChild(removeInput);
            }
        });
    }

    function handleFileSelect(file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type!',
                text: 'Please select a valid image file (JPG, PNG, GIF).',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Validate file size (max 2MB)
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large!',
                text: 'Please select an image smaller than 2MB.',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }
});

// Form validation before submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            
            if (!name) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information!',
                    text: 'Please enter a category name.',
                    confirmButtonText: 'OK'
                });
                document.getElementById('name').focus();
                return false;
            }
        });
    }
});
</script>
@endsection