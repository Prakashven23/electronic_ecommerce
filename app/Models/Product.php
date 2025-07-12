<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount',
        'sale_price',
        'image',
        'category_id',
        'featured',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeBestSellers($query, $limit = 8)
    {
        return $query->withCount(['orderItems as orders_count' => function($q) {
            $q->select(\DB::raw('COUNT(DISTINCT order_id)'));
        }])
        ->orderBy('orders_count', 'desc')
        ->limit($limit);
    }
} 