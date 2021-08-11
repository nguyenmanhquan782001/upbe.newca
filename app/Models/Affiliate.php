<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $table    = "affiliate";
    protected $id       = 'id';
    public $modelName   = 'Affiliate';
    protected $fillable = [
        'id',
        'user_id',
        'referal_id',
        'affiliate_code',
        'total_balance',
        'avaiable_balance',
        'affiliate_banks',
        'created_at',
        'updated_at',
        'settings',
    ];

    protected $casts = [
        'affiliate_banks' => 'array',
    ];

    protected $description_key = 'affiliate_id';

    public function getDescriptionKey()
    {
        return $this->description_key;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(AffiliateTransaction::class, 'affiliate_id', 'id');
    }

    public function ref()
    {
        return $this->where('referal_id', $this->user_id)->get();
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'referal_id', 'id');
    }

    public function inviterAffiliate()
    {
        return $this->belongsTo(Affiliate::class, 'user_id', 'referal_id');
    }

    public function bonusList()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function($aff) {
            $aff->shortLinks()->delete();
            $aff->transactions()->delete();
            $aff->delete();
        });
    }
}
