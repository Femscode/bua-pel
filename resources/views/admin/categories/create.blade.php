@extends('admin.master')

@section('title', 'Add New Category')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/admin-categories.css') }}">
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
   
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Categories</p>  
    </div>
</div>
@endsection

@section('content')
<div class="admin-page">
    <div class="admin-page-header">
        <h1 class="admin-page-title">Create New Category</h1>
        <div class="admin-page-actions">
            <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-outline">
                <i class="fas fa-arrow-left"></i>
                Back to Categories
            </a>
        </div>
    </div>
    <div class="admin-form-container">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm" class="admin-form">
            @csrf
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label for="name" class="admin-form-label">Category Name <span class="required">*</span></label>
                    <input type="text" class="admin-form-input @error('name') error @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="admin-form-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        Active Category
                    </label>
                    @error('is_active')
                        <div class="admin-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- <div class="admin-form-group admin-form-group-full">
                <label for="description" class="admin-form-label">Description</label>
                <textarea class="admin-form-textarea @error('description') error @enderror" 
                          id="description" name="description" rows="4" 
                          placeholder="Enter category description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="admin-form-error">{{ $message }}</div>
                @enderror
            </div> -->
            <div class="admin-form-group admin-form-group-full">
                <label for="image" class="admin-form-label">Category Image</label>
                <div class="admin-file-upload">
                    <div class="admin-file-drop-zone" id="dropZone">
                        <input type="file" class="admin-file-input @error('image') error @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="admin-file-drop-content">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drop image here or <span class="admin-file-browse">browse</span></p>
                            <small>Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>
                    </div>
                    @error('image')
                        <div class="admin-form-error">{{ $message }}</div>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="admin-image-preview">
                        <img id="previewImg" src="" alt="Preview" class="admin-preview-img">
                        <button type="button" class="admin-btn admin-btn-danger admin-btn-sm" id="removeImage">
                            <i class="fas fa-times"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Category
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
    const fileInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeBtn = document.getElementById('removeImage');
    const browseTrigger = document.querySelector('.admin-file-browse');

    // Browse button click
    if (browseTrigger) {
        browseTrigger.addEventListener('click', function() {
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

    // Remove image button
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            imagePreview.style.display = 'none';
        });
    }

    function handleFileSelect(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    }

    // Form validation
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