<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// 商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// ログイン画面
Route::get('/login', [AuthController::class, 'login'])->name('login');
// 新規登録画面
Route::get('/register', [AuthController::class, 'register'])->name('register');

// プロフィール編集画面
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// プロフィール更新
Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

// マイページ画面
Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');

// 出品ページ画面
Route::get('/sell', [ItemController::class, 'sell'])->name('sell');
// 出品機能
Route::post('/sell', [ItemController::class, 'store'])->name('store');

// 購入画面
Route::get('/purchase/{id}', [ItemController::class, 'purchase'])->name('items.purchase');
// 購入ボタン
Route::post('/purchase/{id}', [ItemController::class, 'sold'])->name('items.sold');


// 送付先住所変更画面
Route::get('/purchase/address/{id}', [ItemController::class, 'edit'])->name('address.edit');
// 送付先変更ボタン
Route::put('/purchase/address/{id}', [ItemController::class, 'update'])->name('address.update');
