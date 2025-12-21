<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
      public function index() {
      
        // Get recent orders with user information
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get top products by units sold (simulated with order items count)
        // $topProducts = Product::withCount('orderItems')
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();
        
        // Get dashboard statistics
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        // $totalRevenue = Order::sum('total_amount');
        
        return view('admin.index', compact(
             'recentOrders', 
             'topProducts', 
             'totalOrders', 
             'totalProducts', 
             'totalUsers', 
             'totalRevenue'
         ));
     }
     
     public function orders() {
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.orders', compact('orders'));
    }
    
    public function orderDetails(Order $order) {
        $order->load(['user', 'orderItems.product']);
        return view('admin.order-details', compact('order'));
    }
    
    public function exportOrders()
    {
        $orders = Order::with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = [];
        $csvData[] = ['Order Number', 'Customer Name', 'Email', 'Phone', 'Date', 'Status', 'Total Amount', 'Items Count', 'Products'];

        foreach ($orders as $order) {
            $products = $order->orderItems->map(function($item) {
                return $item->product->name . ' (Qty: ' . $item->quantity . ')';
            })->implode('; ');

            $csvData[] = [
                $order->order_number ?? 'ORD-' . $order->id,
                $order->name,
                $order->email,
                $order->phone ?? 'N/A',
                $order->created_at->format('Y-m-d H:i:s'),
                ucfirst($order->status),
                '₦' . number_format($order->total_amount, 2),
                $order->orderItems->count(),
                $products
            ];
        }

        $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        
        // Add BOM for proper UTF-8 encoding in Excel
        fwrite($handle, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        exit;
    }
    public function products(\Illuminate\Http\Request $request) {
        $query = Product::with('category');

        // Optional filter by category ID
        if ($request->filled('category')) {
            $query->where('product_category_id', $request->input('category'));
        }
        // Return full collection; DataTables will handle client-side pagination/sorting
        $products = $query->get();
        return view('admin.products', compact('products'));
    }

    public function users() {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function make_admin($id) {

        $user = User::findOrFail($id);
        if($user->role == 'admin') {
            $user->role = 'user';
        } else {
            $user->role = 'admin';

        }
        $user->save();
        
        return redirect()->back()->with('success', 'User role updated successfully!');
    }
    
    public function exportUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Registration Date'];

        foreach ($users as $user) {
            $csvData[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->phone ?? 'N/A',
                ucfirst($user->role),
                $user->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        
        // Add BOM for proper UTF-8 encoding in Excel
        fwrite($handle, "\xEF\xBB\xBF");
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        exit;
    }
    
    public function editProduct($id) {
        $product = Product::findOrFail($id);
        $categories = \App\Models\ProductCategory::all();
        return view('admin.edit-product', compact('product', 'categories'));
    }
    
    public function updateProduct(Request $request, $id) {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'product_category_id' => 'required|exists:product_categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'removed_images' => 'nullable|array',
            'tags' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'additional_info' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Get current images
        $currentImages = $product->image ? json_decode($product->image, true) : [];
        
        // If new images are being uploaded, delete all existing images first
        if ($request->hasFile('images')) {
            // Delete all existing images from filesystem
            if (!empty($currentImages)) {
                foreach ($currentImages as $existingImage) {
                    if (file_exists(public_path('uploads/products/' . $existingImage))) {
                        unlink(public_path('uploads/products/' . $existingImage));
                    }
                }
            }
            
            // Reset current images array and upload new ones
            $currentImages = [];
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $currentImages[] = $imageName;
            }
        } else {
            // Handle individual image removal if no new images are uploaded
            if ($request->has('removed_images')) {
                foreach ($request->removed_images as $removedImage) {
                    // Remove from filesystem
                    if (file_exists(public_path('uploads/products/' . $removedImage))) {
                        unlink(public_path('uploads/products/' . $removedImage));
                    }
                    // Remove from current images array
                    $currentImages = array_filter($currentImages, function($img) use ($removedImage) {
                        return $img !== $removedImage;
                    });
                }
            }
        }
        
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'image' => !empty($currentImages) ? json_encode(array_values($currentImages)) : null,
            'product_category_id' => $request->product_category_id,
            'stock_quantity' => $request->stock_quantity,
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : null,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'additional_info' => $request->additional_info,
            'is_active' => $request->has('is_active')
        ]);
        
        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }
    
    public function deleteProduct($id) {
        $product = Product::findOrFail($id);
        
        // Delete associated images from filesystem
        if ($product->image) {
            $images = json_decode($product->image, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    $imagePath = public_path('uploads/products/' . $image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
        }
        
        $product->delete();
        
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    public function bulkDeleteProducts(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $ids = $request->ids;
        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            // Delete associated images
            if ($product->image) {
                $images = json_decode($product->image, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imagePath = public_path('uploads/products/' . $image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                } elseif (is_string($product->image)) {
                     // Check if it's a JSON string first
                     $decoded = json_decode($product->image, true);
                     if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        foreach ($decoded as $image) {
                            $imagePath = public_path('uploads/products/' . $image);
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }
                     } else {
                         // Simple string path
                         $imagePath = public_path('uploads/products/' . $product->image);
                         if (file_exists($imagePath)) {
                            unlink($imagePath);
                         }
                     }
                }
            }
            $product->delete();
        }

        return redirect()->route('admin.products')->with('success', count($ids) . ' products deleted successfully!');
    }
    public function add_product() {
        $categories = \App\Models\ProductCategory::where('is_active', true)->get();
        return view('admin.add-product', compact('categories'));
    }
    
    public function storeProduct(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'discount_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'product_category_id' => 'required|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'additional_info' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $productData = [
            'name' => $request->name,
            'slug'=> Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->quantity,
            'product_category_id' => $request->product_category_id,
            'tags' => $request->tags ? array_map('trim', explode(',', $request->tags)) : null,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'additional_info' => $request->additional_info,
            'is_active' => $request->has('is_active')
        ];
        
        // Handle multiple image uploads
        $imageNames = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $imageNames[] = $imageName;
            }
            $productData['image'] = json_encode($imageNames);
        }
        
        Product::create($productData);
        
        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }
    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.settings')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function changePassword() {
        return view('admin.change-password');
    }

    public function duplicateProduct($id)
    {
        $product = Product::findOrFail($id);

        // Replicate product attributes
        $newProduct = $product->replicate();

        // Append marker to name and create a unique slug
        $newProduct->name = $product->name . ' (Duplicate)';
        $baseSlug = $product->slug ? $product->slug : Str::slug($product->name);
        $newProduct->slug = $baseSlug . '-copy-' . Str::random(4);

        // Preserve images as-is (JSON string or array cast handled by model)
        // Save duplicated product
        $newProduct->save();

        return redirect()->route('admin.products')->with('success', 'Product duplicated successfully!');
    }

    public function exportProducts(Request $request)
    {
        $format = $request->input('format', 'csv');
        $products = Product::with('category')->get();

        if ($format === 'pdf') {
            return view('admin.products-print', compact('products'));
        }

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Category', 'Price', 'Stock', 'Status', 'Description'];

        foreach ($products as $product) {
            $csvData[] = [
                $product->id,
                $product->name,
                $product->category->name ?? 'N/A',
                $product->price,
                $product->stock_quantity,
                $product->is_active ? 'Active' : 'Inactive',
                $product->description
            ];
        }

        $extension = $format === 'excel' ? 'csv' : 'csv'; // Simple Excel support via CSV
        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.' . $extension;
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        
        fwrite($handle, "\xEF\xBB\xBF"); // BOM
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        fclose($handle);
        exit;
    }

    public function downloadProductTemplate()
    {
        $headers = ['Name', 'Category', 'Price', 'Discount Price', 'Stock Quantity', 'Description', 'Tags', 'SEO Title', 'SEO Description'];
        $filename = 'product_import_template.csv';
        
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, $headers);
        
        // Add a sample row
        fputcsv($handle, ['Rexona', 'Perfume', '1000.00', '900.00', '50', 'Description here', 'perfume, men, smell nice', 'SEO Title', 'SEO Description']);
        
        fclose($handle);
        exit;
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ], [
            'file.mimes' => 'Please upload a valid CSV file. For Excel files, please "Save As" CSV.'
        ]);

        $file = $request->file('file');
        
        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            
            // Remove BOM if present
            if (isset($header[0]) && strpos($header[0], "\xEF\xBB\xBF") === 0) {
                $header[0] = substr($header[0], 3);
            }

            // Normalize headers
            $header = array_map(function($h) {
                return strtolower(trim($h));
            }, $header);
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (count($header) !== count($data)) continue;
                
                $row = array_combine($header, $data);
                
                // Find or create category
                $categoryId = null;
                if (!empty($row['category'])) {
                    $category = \App\Models\ProductCategory::firstOrCreate(
                        ['name' => trim($row['category'])],
                        ['slug' => Str::slug($row['category']), 'is_active' => true]
                    );
                    $categoryId = $category->id;
                }
                
                Product::create([
                    'name' => $row['name'] ?? 'Imported Product',
                    'slug' => Str::slug(($row['name'] ?? 'imported') . '-' . Str::random(6)),
                    'description' => $row['description'] ?? '',
                    'price' => $row['price'] ?? 0,
                    'discount_price' => !empty($row['discount price']) ? $row['discount price'] : null,
                    'stock_quantity' => $row['stock quantity'] ?? 0,
                    'product_category_id' => $categoryId,
                    'tags' => isset($row['tags']) ? array_map('trim', explode(',', $row['tags'])) : null,
                    'seo_title' => $row['seo title'] ?? null,
                    'seo_description' => $row['seo description'] ?? null,
                    'is_active' => true
                ]);
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'Products imported successfully!');
    }
}