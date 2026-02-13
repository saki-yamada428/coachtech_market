<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // 書き換え可能項目
    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_code',
        'address',
        'building',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }    
}
