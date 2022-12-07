<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\OrderProduct;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'img',
        'price',
        'stock',
        'sold',
        'is_delete'
    ];

    // public function order() {
    //     return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id');
    // }

    public function order() {
        return $this->hasMany(OrderProduct::class);
    }
}
