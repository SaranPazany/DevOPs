<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','pricing','category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function carts() {
        return $this->hasMany(Cart::class);
    }

    public function wishlists() {
        return $this->hasMany(Wishlist::class);
    }

    public function order_products() {
        return $this->hasMany(OrderProduct::class);
    }
}
