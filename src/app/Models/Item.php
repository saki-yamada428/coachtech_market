<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// 画像のURLの表示分岐
use Illuminate\Support\Str;

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

    // 画像の表示条件分岐（picture_urlで呼び出せる）
    public function getPictureUrlAttribute()
    {
        if (Str::startsWith($this->picture, 'http')) {
            return $this->picture;
        }

        return asset('storage/' . $this->picture);
    }

    // リレーション
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // public function favorites()
    // {
    //     return $this->belongsToMany(User::class, 'favorites')
    //                 ->withTimestamps();
    // }
    // ↑の書き方はLaravelのデフォルト規約に沿う場合のみ使える
    // 中間テーブル名をitem_userにしないとダメ
    // 今回はfavoriteで作ったので使えない

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id')
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
