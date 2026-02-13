<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // リレーション
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    // 書き換え可能項目
    protected $fillable = [
        'name',
    ];

}
