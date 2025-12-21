<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   
    public function dashboard() {
        $user = Auth::user();
       
        if($user->role == 'admin') {
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
        // $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalRevenue = Order::sum('total_amount');
        
        return view('admin.index', compact(
             'recentOrders', 
             'topProducts', 
             'totalOrders', 
             'totalProducts', 
             'totalUsers', 
             'totalRevenue'
         ));
        } else {
            $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount');
        // $totalSpent = $user->orders()->sum('total_amount');
        
        // Get recent orders
        $recentOrders = $user->orders()
                           ->with(['orderItems.product'])
                           ->latest()
                           ->limit(5)
                           ->get();
        
        return view('user.index', compact(
            'user',
            'totalOrders',
            'pendingOrders', 
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
        }
    }
    public function index() {
        $user = Auth::user();
        
        // Get user statistics
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount');
        // $totalSpent = $user->orders()->sum('total_amount');
        
        // Get recent orders
        $recentOrders = $user->orders()
                           ->with(['orderItems.product'])
                           ->latest()
                           ->limit(5)
                           ->get();
        
        return view('user.index', compact(
            'user',
            'totalOrders',
            'pendingOrders', 
            'completedOrders',
            'totalSpent',
            'recentOrders'
        ));
    }
    
    public function order() {
        return view('user.order');
    }
    
    public function changePassword() {
        return view('user.change-password');
    }
}
