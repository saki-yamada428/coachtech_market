<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    // 書き換え可能項目
    protected $fillable = [
        'name',
    ];

    // リレーション
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
