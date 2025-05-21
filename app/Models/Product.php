<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // These fields can be filled in using create() or update()
    protected $fillable = ['name', 'description', 'slug', 'price', 'active'];

    // A product has many images
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // A product has one discount
    public function discount()
    {
        return $this->hasOne(ProductDiscount::class);
    }
}
