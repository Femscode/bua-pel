<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:100'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        // Check if product exists and is available
        $product = Product::findOrFail($productId);
        
        // Find existing cart item
        $cartItem = Cart::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($cartItem) {
            // Update existing cart item
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:100'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $cartItem = Cart::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        if ($quantity == 0) {
            $cartItem->delete();
            $message = 'Product removed from cart';
        } else {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            $message = 'Cart updated successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $cartItem = Cart::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function getCartItems()
    {
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

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems,
            'cart_count' => $cartItems->sum('quantity')
        ]);
    }

    public function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        return Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->sum('quantity');
    }

    public function getProductCartQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = $userId ? null : Session::getId();

        $cartItem = Cart::where('product_id', $productId)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        return response()->json([
            'success' => true,
            'quantity' => $cartItem ? $cartItem->quantity : 0
        ]);
    }
}
