<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }


    // public function product() {
    //     return $this->belongsToMany(Product::class, 'order_products', 'product_id', 'order_id');
    // }

    public function orderProduct() {
        return $this->hasMany(OrderProduct::class);
    }


}
