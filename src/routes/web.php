<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Route::get('/', function () {
//     return view('welcome');
// });

// 商品
// Route::resource('items', ItemController::class);

// お気に入り
// Route::post('/items/{item}/favorite', [FavoriteController::class, 'store'])
//     ->name('favorites.store');

// Route::delete('/items/{item}/favorite', [FavoriteController::class, 'destroy'])
//     ->name('favorites.destroy');

// プロフィール
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// });

// ログイン・新規登録の画面表示はFortifyServiceProvider.phpに記述
// ログイン後の画面表示
// Route::middleware(['auth'])->group(function () {
//     Route::get('/', function () {
//         return view('items.index');
//     })->name('home');
// });

// 商品一覧
// Route::middleware(['auth'])->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('items.index');
// });

// 商品詳細
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// ログイン画面
Route::get('/login', [AuthController::class, 'login'])->name('login');
// 新規登録画面
Route::get('/register', [AuthController::class, 'register'])->name('register');

// プロフィール編集画面
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// プロフィール更新
Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

// マイページ画面
Route::get('/mypage', [ProfileController::class, 'mypage'])->name('mypage');
