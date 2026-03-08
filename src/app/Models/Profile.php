<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    // 書き換え可能項目
    protected $fillable = [
        'user_id',
        'nickname',
        'picture',
        'postal_code',
        'address',
        'building',
    ];

    // リレーション
    public function user(){
        return $this->belongsTo(User::class);
    }
}
