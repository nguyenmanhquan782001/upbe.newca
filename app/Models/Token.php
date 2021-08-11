<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table    = "user_token";
    protected $id       = 'id';
    public $modelName   = 'Token';
    protected $fillable = [
        'user_id',
        'cn',
        'serial',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    static function createOrUpdate($data, $keys) {
        $record = self::where($keys)->first();
        if (is_null($record)) {
            return self::create($data);
        } else {
            return self::where($keys)->update($data);
        }
    }
}
