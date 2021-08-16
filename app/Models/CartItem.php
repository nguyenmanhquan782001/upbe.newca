<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = "cart_item";
    protected $primaryKey = "id";
    protected $fillable = ['cart_id' , 'product_id'];
    public function infoProduct(){
        return $this->belongsTo(Product::class , 'product_id');
    }


}
