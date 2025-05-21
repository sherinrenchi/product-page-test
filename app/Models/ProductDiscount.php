<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    protected $fillable = ['product_id', 'type', 'discount'];

    // A discount belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
