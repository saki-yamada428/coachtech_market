<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;

// メール認証
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


// 商品一覧ページ
Route::get('/', [ItemController::class, 'index'])->name('items.index');
// 商品検索
Route::get('/search', [ItemController::class, 'search'])->name('items.search');


// 商品詳細
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
// お気に入り追加
Route::post('/items/{item}', [FavoriteController::class, 'store'])
    ->middleware('auth', 'verified')
    ->name('favorite.store');
// お気に入り削除
Route::delete('/items/{item}', [FavoriteController::class, 'destroy'])
    ->middleware('auth', 'verified')
    ->name('favorite.destroy');
// コメント送信
Route::post('/items/{id}/comments', [ItemController::class, 'comments'])
    ->middleware('auth', 'verified')
    ->name('items.comments');


// ログイン画面
Route::get('/login', [AuthController::class, 'login'])->name('login');
// 新規登録画面
Route::get('/register', [AuthController::class, 'register'])->name('register');

// メール認証画面
Route::get('/email/verify', function (){
        return view('auth.email');
    })
    ->middleware('auth')
    ->name('verification.notice');

// メール内リンクをクリックしたとき
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');
// 認証メールの再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



// プロフィール編集画面
Route::get('/mypage/profile', [ProfileController::class, 'edit'])
    ->middleware('auth', 'verified')
    ->name('profile.edit');
// プロフィール更新
Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

// マイページ画面
Route::get('/mypage', [ProfileController::class, 'mypage'])
    ->middleware('auth', 'verified')
    ->name('mypage');

// 出品ページ画面
Route::get('/sell', [ItemController::class, 'sell'])
    ->middleware('auth', 'verified')
    ->name('sell');
// 出品ボタン
Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

// 購入画面
Route::get('/purchase/{id}', [ItemController::class, 'purchase'])
    ->middleware('auth', 'verified')
    ->name('items.purchase');
// 購入ボタン
Route::post('/purchase/{id}', [ItemController::class, 'sold'])->name('items.sold');
// Stripe決済成功後
Route::get('/purchase/{id}/success', [ItemController::class, 'success'])->name('purchase.success');

// 送付先住所変更画面
Route::get('/purchase/address/{id}', [ItemController::class, 'edit'])
    ->middleware('auth')
    ->name('address.edit');
// 送付先変更ボタン
Route::put('/purchase/address/{id}', [ItemController::class, 'update'])->name('address.update');
