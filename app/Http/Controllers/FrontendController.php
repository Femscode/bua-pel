<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Cart;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    public function index() {
        // Get random products for different sections
        
        
        $dealOfTheDay = Product::where('is_active', true)
            ->inRandomOrder()
            ->first();
        
        $popularProducts = Product::where('is_active', true)
            ->inRandomOrder()
           
            ->get();
        
        // Get active categories with product count
        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->get();
        
        return view('frontend.index', compact( 'dealOfTheDay', 'popularProducts', 'categories'));
    }
    
    public function shop() {
        // Get random products for shop page
        $products = Product::where('is_active', true)
            ->inRandomOrder()
            ->paginate(12);
        $otherproducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->paginate(12);
        
        $featuredProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $recentProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->get();
        
        return view('frontend.shop', compact('products','otherproducts', 'featuredProducts','recentProducts', 'categories'));
    }
    
    public function shopByCategory($slug) {
        // Find the category by slug
        $category = ProductCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        // Get products for this category
        $products = Product::where('is_active', true)
            ->where('product_category_id', $category->id)
            ->paginate(12);
        
        // Get other products (not in this category) for recommendations
        $otherproducts = Product::where('is_active', true)
            ->where('product_category_id', '!=', $category->id)
            ->inRandomOrder()
            ->paginate(12);
        
        $featuredProducts = Product::where('is_active', true)
            ->where('product_category_id', $category->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $recentProducts = Product::where('is_active', true)
            ->where('product_category_id', $category->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
            $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->get();
        
        return view('frontend.shop', compact('products','otherproducts', 'featuredProducts','recentProducts', 'category', 'categories'));
    }

    public function search(Request $request) {
        $query = $request->get('q');
        $brand = $request->get('brand');
        $category = $request->get('category');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        
        // Start with base query
        $productsQuery = Product::where('is_active', true)->with('category');
        
        // Search in product name, description, and brand
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                //   ->orWhere('brand', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'LIKE', "%{$query}%");
                  });
            });
        }
        
        // Filter by brand
        if ($brand) {
            // $productsQuery->where('brand', 'LIKE', "%{$brand}%");
        }
        
        // Filter by category
        if ($category) {
            $productsQuery->whereHas('category', function($categoryQuery) use ($category) {
                $categoryQuery->where('slug', $category);
            });
        }
        
        // Filter by price range
        if ($minPrice) {
            $productsQuery->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $productsQuery->where('price', '<=', $maxPrice);
        }

        $products = $productsQuery->paginate(12);
        
        // Get other products for recommendations
        $otherproducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(12)
            ->get();
        
        $featuredProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $recentProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        $categories = ProductCategory::where('is_active', true)
            ->withCount('products')
            ->get();
        
        // Get unique brands for filter
        // $brands = Product::where('is_active', true)
        //     ->whereNotNull('brand')
        //     ->where('brand', '!=', '')
        //     ->distinct()
        //     ->pluck('brand')
        //     ->sort();
        $brands = null;
        
        return view('frontend.shop', compact('products','otherproducts', 'featuredProducts','recentProducts', 'categories', 'query', 'brands', 'brand', 'category', 'minPrice', 'maxPrice'));
    }
    public function productDetails($id) {
        // $product = Product::with('category')->findOrFail($id);
        $product = Product::with('category')->where('slug',$id)->first();
        
        // Get related products from the same category
        $relatedProducts = Product::where('is_active', true)
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        return view('frontend.product-details', compact('product', 'relatedProducts'));
    }
    public function cart() {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $cartItems = Cart::with('product')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();

        $subtotal = $cartItems->sum(function($item) {
            $price = method_exists($item->product, 'getFinalPrice')
                ? $item->product->getFinalPrice()
                : ($item->product->discount_price && $item->product->discount_price < $item->product->price
                    ? $item->product->discount_price
                    : $item->product->price);
            return $item->quantity * $price;
        });

        $discount = 0; // You can implement discount logic here
        $tax = $subtotal * 0.075; // 7.5% tax
        $total = $subtotal - $discount + $tax;

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discount', 'tax', 'total'));
    }
    public function checkout()
    {
        // Get cart items
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        } else {
            $sessionId = Session::getId();
            $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            $price = method_exists($item->product, 'getFinalPrice')
                ? $item->product->getFinalPrice()
                : ($item->product->discount_price && $item->product->discount_price < $item->product->price
                    ? $item->product->discount_price
                    : $item->product->price);
            return $price * $item->quantity;
        });
        
        $shippingCost = 25500; // Fixed shipping cost
        $tax = $subtotal * 0.075; // 7.5% tax
        $total = $subtotal + $tax + $shippingCost;

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'tax', 'shippingCost', 'total'));
    }
    public function about() {
        return view('frontend.about-us');
    }
    public function contact() {
        return view('frontend.contact-us');
    }
    
    public function contactSubmit(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'newsletter' => 'boolean'
        ]);
        
        ContactUs::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'newsletter' => $request->has('newsletter')
        ]);
        
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
