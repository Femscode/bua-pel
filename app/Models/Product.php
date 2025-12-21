<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'image',
        'images',
        'product_category_id',
        'tags',
        'seo_title',
        'seo_description',
        'additional_info',
        'is_active',
        'stock_quantity',
        'has_tax',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'images' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'has_tax' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Get the orders for the product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the final price (considering discount)
     */
    public function getFinalPrice()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if product is on sale
     */
    public function isOnSale()
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }
}
