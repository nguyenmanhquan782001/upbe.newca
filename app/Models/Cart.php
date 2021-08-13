<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $guarded = [];

    public function cartItem()
    {
        return $this->belongsToMany(Product::class,
            'cart_item',
            'cart_id',
            'product_id');
    }
    public function  cartI(){
        return $this->hasMany(CartItem::class , 'cart_id');
    }


}
