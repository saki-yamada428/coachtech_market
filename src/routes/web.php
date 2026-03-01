<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;

// 商品一覧
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 商品検索
Route::post('/', [ItemController::class, 'search'])->name('items.search');


// 商品詳細
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
// お気に入り追加
Route::post('/items/{item}', [FavoriteController::class, 'store'])
    ->middleware('auth')
    ->name('favorite.store');
// お気に入り削除
Route::delete('/items/{item}', [FavoriteController::class, 'destroy'])
    ->middleware('auth')
    ->name('favorite.destroy');
// コメント送信
Route::post('/items/{id}/comments', [ItemController::class, 'comments'])
    ->middleware('auth')
    ->name('items.comments');


// ログイン画面
Route::get('/login', [AuthController::class, 'login'])->name('login');
// 新規登録画面
Route::get('/register', [AuthController::class, 'register'])->name('register');

// プロフィール編集画面
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// プロフィール更新
Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

// マイページ画面
Route::get('/mypage', [ProfileController::class, 'mypage'])
    ->middleware('auth')
    ->name('mypage');

// 出品ページ画面
Route::get('/sell', [ItemController::class, 'sell'])
    ->middleware('auth')
    ->name('sell');
// 出品ボタン
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

// 購入画面
Route::get('/purchase/{id}', [ItemController::class, 'purchase'])
    ->middleware('auth')
    ->name('items.purchase');
// 購入ボタン
Route::post('/purchase/{id}', [ItemController::class, 'sold'])->name('items.sold');
// Stripe決済成功後
Route::get('/purchase/{id}/success', [ItemController::class, 'success'])->name('purchase.success');

// 送付先住所変更画面
Route::get('/purchase/address/{id}', [ItemController::class, 'edit'])->name('address.edit');
// 送付先変更ボタン
Route::put('/purchase/address/{id}', [ItemController::class, 'update'])->name('address.update');
