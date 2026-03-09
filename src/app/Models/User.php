<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// メール認証
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // リレーション
    public function profile(){
        return $this->hasOne(Profile::class);
    }

    // public function favorites(){
    //     return $this->belongsToMany(Item::class, 'favorites')
    //                 ->withTimestamps();
    // }
    // ↑の書き方はLaravelのデフォルト規約に沿う場合のみ使える
    // 中間テーブル名をitem_userにしないとダメ
    // 今回はfavoriteで作ったので使えない

    public function favoriteItems(){
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id')
                    ->withTimestamps();
    }

    // 表記ゆれの訂正
    public function favorites(){
    return $this->favoriteItems();
}

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }


}
