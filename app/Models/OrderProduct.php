<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

}
