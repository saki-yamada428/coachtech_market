<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Item;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(); // プロフィール未作成でもOK

        return view('users.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile();
        $profile->user_id = $user->id;

        $request->validate([
            'nickname' => 'required|string|max:20',
            'picture' => 'nullable|image|mimes:jpeg,png|max:2048',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string',
            'building' => 'nullable|string',
        ]);

        // テキスト項目の更新
        $profile->nickname = $request->nickname;
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;

        // 画像が送られてきた場合
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('png', 'public');
            $profile->picture = $path;
        }

        // dd($request->file('picture'));
                // dd($request->file('picture'));

        $profile->save();


        return redirect('/')->with('success', 'プロフィールを更新しました');
        // return back()->with('success', 'プロフィールを更新しました');
    }

    public function mypage()
    {
        $user = Auth::user();       // ログイン中のユーザー
        $profile = $user->profile;  // リレーションでプロフィール取得
        // 全商品を取得
        $items = Item::paginate(8);

        return view('users.mypage', compact('user', 'profile','items'));
    }
}
