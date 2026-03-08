<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    // 書き換え可能項目
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    // リレーション
    public function Item() {
        return $this->belongsTo(Item::class);
    }

    public function User() {
        return $this->belongsTo(User::class);
    }
}
