<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    // 書き換え可能項目
    protected $fillable = [
        'name',
    ];
}
