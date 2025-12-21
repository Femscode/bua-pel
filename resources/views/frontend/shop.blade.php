@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/shop.css') }}" />
@endsection

@section('content')
<div class="sh-breadcrumb">
    <a href="/" class="sh-breadcrumb-button"><div class="sh-breadcrumb-item">Home</div></a>
    <div class="sh-breadcrumb-divider">/</div>
    <div class="sh-breadcrumb-wrapper">
        <a href='/shop'><div class="sh-breadcrumb-item">Shop</div></a>
    </div>
    @if(isset($category) && is_object($category))
        <div class="sh-breadcrumb-divider">/</div>
        <div class="sh-breadcrumb-wrapper"><a href="{{ route('shop.category', $category->slug) }}" class="sh-breadcrumb-current">{{ $category->name }}</a></div>
    @endif
</div>

<div class="sh-categories">
    <div class="sh-categories-div">
        <div class="sh-categories-all">
            @if(isset($categories) && $categories->count() > 0)
                <div class="category-grid-shop">
                    @foreach($categories as $category)
                        <div class="category-card-shop">
                            <a href="{{ route('shop.category', $category->slug) }}" class="category-link-shop">
                                <div class="category-content-shop">
                                    <div class="category-title-shop">{{ $category->name }}</div>
                                    <div class="category-arrow-shop">→</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="category-grid-shop">
                    <div class="category-card-shop">
                        <div class="category-content-shop">
                            <div class="category-title-shop">No Categories Available</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="sh-categories-filter-box">
            <div class="sh-categories-filter-header"><div class="sh-categories-filter-title">FILTERS</div></div>
            <form action="{{ route('search') }}" method="GET" id="filter-form">
                <!-- Preserve search query -->
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                
                <!-- Preserve brand filter if it exists -->
                @if(request('brand'))
                    <input type="hidden" name="brand" value="{{ request('brand') }}">
                @endif
                
                <div class="sh-categories-filter-mobile">
                    <select class="sh-categories-filter-select" name="category">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $categoryOption)
                                <option value="{{ $categoryOption->slug }}" {{ request('category') == $categoryOption->slug ? 'selected' : '' }}>
                                    {{ $categoryOption->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="sh-categories-filter-price">
                        <select class="sh-categories-filter-select" name="min_price">
                            <option value="">Min Price: ₦0</option>
                            <option value="10000" {{ request('min_price') == '10000' ? 'selected' : '' }}>₦10,000</option>
                            <option value="50000" {{ request('min_price') == '50000' ? 'selected' : '' }}>₦50,000</option>
                            <option value="100000" {{ request('min_price') == '100000' ? 'selected' : '' }}>₦100,000</option>
                        </select>
                        <select class="sh-categories-filter-select" name="max_price">
                            <option value="">Max Price: ₦1,000,000</option>
                            <option value="50000" {{ request('max_price') == '50000' ? 'selected' : '' }}>₦50,000</option>
                            <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>₦100,000</option>
                            <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>₦200,000</option>
                            <option value="500000" {{ request('max_price') == '500000' ? 'selected' : '' }}>₦500,000</option>
                        </select>
                    </div>
                    <button type="submit" class="sh-categories-filter-button">Apply Filters</button>
                </div>
                <div class="sh-categories-filter-desktop">
                    <div class="sh-categories-filter-group">
                        <div class="sh-categories-filter-label">Sort By Categories</div>
                        <select class="sh-categories-filter-select" name="category" style="width: 100%; margin-top: 10px;">
                            <option value="">All Categories</option>
                            @if(isset($categories))
                                @foreach($categories as $categoryOption)
                                    <option value="{{ $categoryOption->slug }}" {{ request('category') == $categoryOption->slug ? 'selected' : '' }}>
                                        {{ $categoryOption->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="sh-categories-filter-group-price">
                        <div class="sh-categories-filter-label">Sort By Price</div>
                        <div class="sh-categories-price-range">
                            <div class="sh-categories-price-slider">
                                <div class="sh-categories-price-progress">
                                    <div class="sh-categories-price-handle-min"></div>
                                    <div class="sh-categories-price-handle-max"></div>
                                </div>
                            </div>
                        </div>
                        <div class="sh-categories-price-inputs">
                            <div class="sh-categories-price-field">
                                <input type="number" name="min_price" value="{{ request('min_price', 0) }}" placeholder="₦0" class="sh-categories-price-value" style="border: none; background: transparent; width: 100%;">
                            </div>
                            <div class="sh-categories-price-separator"></div>
                            <div class="sh-categories-price-field">
                                <input type="number" name="max_price" value="{{ request('max_price', 1000000) }}" placeholder="₦1,000,000" class="sh-categories-price-value" style="border: none; background: transparent; width: 100%;">
                            </div>
                        </div>
                        <button type="submit" class="sh-categories-price-button"><div class="sh-categories-price-button-text">Go</div></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="sh-categories-png" style="
        /* background-image: url('{{ asset("homepage/images/home/main1.png") }}');  */
        background-size:cover">
            <div class="sh-categories-info">
                <p class="sh-categories-mobok">
                    <span class="sh-categories-text-bold">Look Good, Feel Good<br /></span>
                    <span class="sh-categories-text-light">Shop Best4UArena</span>
                </p>
                <p class="sh-categories-starting-from">
                    <span class="sh-categories-text">Starting from </span>
                    <span class="sh-categories-price">₦5,000</span>
                </p>
            </div>
        </div>
    </div>
    <div class="sh-categories-best-seller">
        <div class="sh-categories-best-title">{{ isset($category) ? strtoupper($category->name) . ' PRODUCTS' : 'ALL PRODUCTS' }}</div>
        <div class="sh-categories-best-products">
            @if($products && $products->count() > 0)
                @foreach($products as $product)
                <div class="sh-categories-best-item">
                    <div class="sh-categories-best-product">
                        <a href="{{ route('prd', $product->slug) }}" style="text-decoration: none; color: inherit;">
                            <div class="sh-categories-best-img-wrapper">
                                @php
                                    $images = json_decode($product->image, true);
                                    $firstImage = $images[0] ?? $product->image;
                                @endphp
                                @php $imageUrl = asset('uploads/products/' . $firstImage); @endphp
                                <div class="sh-categories-best-img" style="background-image: url('{{ $imageUrl }}'); background-size: cover; background-position: center;"></div>
                            </div>
                        </a>
                        <div class="sh-categories-best-details">
                            <a href="{{ route('prd', $product->slug) }}" style="text-decoration: none; color: inherit;">
                                <div class="sh-categories-best-title-text">{{ Str::limit($product->name, 20) }}</div>
                            </a>
                            <div class="sh-categories-best-category">{{ $product->category->name ?? 'Product' }}</div>
                        </div>
                        <div class="sh-categories-best-price">
                            @if($product->discount_price && $product->discount_price < $product->price)
                                <div class="sh-categories-best-current-price">₦{{ number_format($product->discount_price, 0) }}</div>
                                <div class="sh-categories-best-old-price">₦{{ number_format($product->price, 0) }}</div>
                            @else
                                <div class="sh-categories-best-current-price">₦{{ number_format($product->price, 0) }}</div>
                            @endif
                        </div>
                        <div class="sh-categories-best-status">
                            @if($product->stock_quantity > 0)
                                <div class="sh-categories-best-status-icon"><img src='{{ url("homepage/images/svgs/active.svg") }}' alt="active"/></div>
                                <div class="sh-categories-best-status-text">In stock</div>
                            @else
                                <div class="sh-categories-best-status-icon"><img src='{{ url("homepage/images/svgs/cancel.svg") }}' alt="cancel"/></div>
                                <div class="sh-categories-best-status-text">Out of Stock</div>
                            @endif
                        </div>
                    </div>
                    <div class="sh-categories-best-action" data-product-id="{{ $product->id }}">
                        <div class="sh-categories-best-action-link add-to-cart-btn" data-product-id="{{ $product->id }}" style="cursor: pointer;"><div class="sh-categories-best-action-text">ADD TO CART</div></div>
                    </div>
                </div>
                @endforeach
            @else
              <p>No products found</p>
            @endif
        </div>
        @if($products->hasPages())
        <div class="sh-categories-pagination">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>



@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const recentlyViewed = document.querySelector('.sh-recently-viewed-scrolling');
    const prevButton = document.querySelector('.sh-recently-viewed-vector-wrapper');
    const nextButton = document.querySelector('.sh-recently-viewed-img-wrapper');

    if (recentlyViewed && prevButton && nextButton) {
        prevButton.addEventListener('click', () => {
            recentlyViewed.scrollBy({ left: -150, behavior: 'smooth' });
        });

        nextButton.addEventListener('click', () => {
            recentlyViewed.scrollBy({ left: 150, behavior: 'smooth' });
        });
    }
});
</script>


<!-- Cart functionality -->
<script src="{{ asset('js/cart.js') }}"></script>
@endsection
