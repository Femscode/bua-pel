@extends('admin.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/add-product.css') }}" />
@endsection

@section('breadcrumb')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
        <div class="solar-breadcrumb-item">Home</div>
    </button>
    
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <p class="solar-breadcrumb-current">Add Product</p>
    </div>
</div>
@endsection

@section('content')


<div class="dashboard">
  <form class="product-formd" method="POST" action="{{ route('admin.store_product') }}" enctype="multipart/form-data">
    @csrf
    <section class="information">
      <h2>Information</h2>
      <div class="form-group">
        <label for="product-name">Product Name</label>
        <input type="text" id="product-name" name="name" placeholder="Enter product name" value="{{ old('name') }}" required>
        @error('name')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="product-description">Product Description</label>
        <textarea id="product-description" name="description" placeholder="Enter product description" rows="4" required>{{ old('description') }}</textarea>
        @error('description')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="additional-info">Additional Info (Optional)</label>
        <textarea id="additional-info" name="additional_info" placeholder="Enter additional product details (optional)" rows="3">{{ old('additional_info') }}</textarea>
        @error('additional_info')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label>Product Images</label>
        <div class="upload-area" id="upload-area">
          <input type="file" id="image-upload" name="images[]" accept="image/*" multiple hidden>
          <button type="button" id="upload-button">Add Images</button>
          <p>Or drag and drop multiple images</p>
        </div>
        <div id="image-preview-container" class="image-preview-container"></div>
        @error('images')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
        @error('images.*')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <h3>Price & Inventory</h3>
        <div class="price-fields">
          <div class="form-group">
            <label for="product-price">Product Price (₦)</label>
            <input type="number" id="product-price" name="price" placeholder="Enter price" min="0" step="0.01" value="{{ old('price') }}" required>
            @error('price')
              <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="discount-price">Discount Price (₦)</label>
            <input type="number" id="discount-price" name="discount_price" placeholder="Enter discount price" min="0" step="0.01" value="{{ old('discount_price') }}">
            @error('discount_price')
              <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label for="quantity">Quantity in Stock</label>
            <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" min="0" value="{{ old('quantity', 0) }}" required>
            @error('quantity')
              <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="toggle-group">
          <label for="is-active">Product is Active</label>
          <input type="checkbox" id="is-active" name="is_active" class="toggle" {{ old('is_active') ? 'checked' : '' }} value="1">
        </div>
      </div>
    </section>
    <br>
    <section class="categories">
      <h2>Category</h2>
      <div class="form-group">
        <label for="product-category">Select Category</label>
        <select id="product-category" name="product_category_id" required>
          <option value="">Choose a category</option>
          @if(isset($categories))
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          @endif
        </select>
        @error('product_category_id')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <a href="{{ route('admin.categories.create') }}" class="create-new" style="display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Create New Category</a>
    </section>
    <br>
    <section class="tags">
      <h2>Tags</h2>
      <div class="form-group">
        <label for="tag-input">Add Tags</label>
        <input type="text" id="tag-input" name="tag_input" placeholder="Enter tag name and press Enter">
        <input type="hidden" id="tags-hidden" name="tags" value="{{ old('tags') }}">
        @error('tags')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="tag-list" id="tag-list">
        <!-- Tags will be dynamically added here -->
      </div>
    </section>
    <br>
    <section class="seo-settings">
      <h2>SEO Settings <span style="color: #5a607f; font-size: 0.875rem;">(Optional)</span></h2>
      <div class="form-group">
        <label for="seo-title">SEO Title</label>
        <input type="text" id="seo-title" name="seo_title" placeholder="Enter SEO title (optional)" value="{{ old('seo_title') }}">
        @error('seo_title')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label for="seo-description">SEO Description</label>
        <textarea id="seo-description" name="seo_description" placeholder="Enter SEO description (optional)" rows="4">{{ old('seo_description') }}</textarea>
        @error('seo_description')
          <span class="error-text" style="color: red; font-size: 12px;">{{ $message }}</span>
        @enderror
      </div>
    </section><br>
    <button type="submit" class="submit-button">Submit</button>
  </form>

</div>
@endsection

@section('script')
  <script>
     document.addEventListener('DOMContentLoaded', function() {
       const hasErrors = {!! json_encode($errors->any()) !!};
       const errorMessages = {!! json_encode($errors->all()) !!};
       const successMessage = {!! json_encode(session('success')) !!};
       const errorMessage = {!! json_encode(session('error')) !!};

       if (hasErrors && Array.isArray(errorMessages) && errorMessages.length) {
         Swal.fire({
           icon: 'error',
           title: 'Validation Error!',
           html: errorMessages.join('<br>'),
           confirmButtonText: 'OK'
         });
       }

       if (successMessage) {
         Swal.fire({
           icon: 'success',
           title: 'Success!',
           text: successMessage,
           timer: 3000,
           showConfirmButton: false
         });
       }

       if (errorMessage) {
         Swal.fire({
           icon: 'error',
           title: 'Error!',
           text: errorMessage,
           confirmButtonText: 'OK'
         });
       }
     });
   </script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    });
    
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('image-upload');
    const uploadButton = document.getElementById('upload-button');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    let selectedFiles = [];

    uploadButton.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = Array.from(e.dataTransfer.files);
      handleFiles(files);
    });

    fileInput.addEventListener('change', () => {
      const files = Array.from(fileInput.files);
      handleFiles(files);
    });

    function handleFiles(files) {
      files.forEach(file => {
        if (file.type.startsWith('image/')) {
          selectedFiles.push(file);
          createImagePreview(file, selectedFiles.length - 1);
        }
      });
      updateFileInput();
    }

    function createImagePreview(file, index) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewItem = document.createElement('div');
        previewItem.className = 'image-preview-item';
        previewItem.innerHTML = `
          <img src="${e.target.result}" alt="Preview">
          <button type="button" class="image-delete-btn" onclick="removeImage(${index})">&times;</button>
          <div class="image-name">${file.name}</div>
        `;
        imagePreviewContainer.appendChild(previewItem);
      };
      reader.readAsDataURL(file);
    }

    function removeImage(index) {
      selectedFiles.splice(index, 1);
      updateImagePreviews();
      updateFileInput();
    }

    function updateImagePreviews() {
      imagePreviewContainer.innerHTML = '';
      selectedFiles.forEach((file, index) => {
        createImagePreview(file, index);
      });
    }

    function updateFileInput() {
      const dt = new DataTransfer();
      selectedFiles.forEach(file => dt.items.add(file));
      fileInput.files = dt.files;
    }

    window.removeImage = removeImage;
    
    // Tag management functionality
    const tagInput = document.getElementById('tag-input');
    const tagList = document.getElementById('tag-list');
    const tagsHidden = document.getElementById('tags-hidden');
    let tags = [];
    
    // Load existing tags from old input
    if (tagsHidden.value) {
      tags = tagsHidden.value.split(',').filter(tag => tag.trim() !== '');
      updateTagDisplay();
    }
    
    tagInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        addTag();
      }
    });
    
    function addTag() {
      const tagValue = tagInput.value.trim();
      if (tagValue && !tags.includes(tagValue)) {
        tags.push(tagValue);
        updateTagDisplay();
        updateHiddenInput();
        tagInput.value = '';
      }
    }
    
    function removeTag(tagToRemove) {
      tags = tags.filter(tag => tag !== tagToRemove);
      updateTagDisplay();
      updateHiddenInput();
    }
    
    function updateTagDisplay() {
      tagList.innerHTML = '';
      tags.forEach(tag => {
        const tagElement = document.createElement('span');
        tagElement.className = 'tag';
        tagElement.innerHTML = `${tag} <button type="button" class="remove-tag" onclick="removeTag('${tag}')">&times;</button>`;
        tagList.appendChild(tagElement);
      });
    }
    
    function updateHiddenInput() {
      tagsHidden.value = tags.join(',');
    }
    
    window.removeTag = removeTag;
    
    // Form validation before submission
    document.querySelector('.product-form').addEventListener('submit', function(e) {
      const name = document.getElementById('product-name').value.trim();
      const description = document.getElementById('product-description').value.trim();
      const price = document.getElementById('product-price').value;
      const quantity = document.getElementById('quantity').value;
      const category = document.getElementById('product-category').value;
      
      if (!name || !description || !price || !quantity || !category) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Missing Information!',
          text: 'Please fill in all required fields.',
          confirmButtonText: 'OK'
        });
        return false;
      }
      
      if (parseFloat(price) <= 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Price!',
          text: 'Product price must be greater than 0.',
          confirmButtonText: 'OK'
        });
        return false;
      }
      
      if (parseInt(quantity) < 0) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Quantity!',
          text: 'Quantity cannot be negative.',
          confirmButtonText: 'OK'
        });
        return false;
      }
    });
  </script>
@endsection
