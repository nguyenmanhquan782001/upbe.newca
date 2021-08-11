<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $table = "points" ;
    protected $primaryKey = "id" ;
    protected $fillable = ['user_id' , 'point'] ;
    public  function  user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
