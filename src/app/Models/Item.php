<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    // 書き換え可能項目
    protected $fillable = [
        'user_id',
        'name',
        'picture',
        'brand',
        'price',
        'description',
        'condition_id',
    ];

    // リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->withTimestamps();
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
