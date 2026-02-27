<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Item;
use App\Models\Order;

// バリデーション
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    // プロフィール編集画面
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(); // プロフィール未作成でもOK

        return view('users.profile', compact('user', 'profile'));
    }

    // プロフィール更新ボタン
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        // 現在の画像のpass（新規登録時はnull）を＄passに入れる
        $path = $user->profile->picture ?? null;
        // 画像が送られてきた場合のみ保存＆$passの中身更新
        if ($request->file('picture')) {
            // 画像保存
            $path = $request->file('picture')->store('png', 'public');
        }

        // テキスト項目の更新or作成
        $profile = Profile::updateOrCreate(
            // 第1引数：検索条件（updateOrCreateのルール）
            ['user_id' => auth()->id()],

            // 第2引数：更新内容（null を許容）
            [
            'nickname' => $request->nickname,
            'picture' => $path,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            ]
        );

        return redirect('/')->with('success', 'プロフィールを更新しました');
    }

    // マイページ画面
    public function mypage()
    {
        $user = Auth::user();       // ログイン中のユーザー
        $profile = $user->profile;  // リレーションでプロフィール取得
        // 全商品を取得
        $items = Item::with('order')->get();

        return view('users.mypage', compact('user', 'profile','items'));
    }
}
