<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // public function index2(Request $request)
    // {
    //     $query = Item::query();

    //     // お気に入り絞り込み
    //     if ($request->favorite == 1 && auth()->check()) {
    //         $query->whereHas('favoritedUsers', function ($q) {
    //             $q->where('user_id', auth()->id());
    //         });
    //     }

    //     $items = $query->paginate(12);

    //     // 各商品が「お気に入り済みか」を判定（表示用）
    //     if (auth()->check()) {
    //         $items->each(function ($item) {
    //             $item->is_favorited = $item->favoritedUsers()
    //                 ->where('user_id', auth()->id())
    //                 ->exists();
    //         });
    //     }

    //     return view('items.index', compact('items'));
    // }

    // 一覧画面表示
    public function index()
    {
        // 全商品を取得
        $items = Item::paginate(8);

        return view('items.index', compact('items'));
    }

    // 商品詳細画面表示
    public function show($id)
    {
        // ID が一致するItemを取得、Itemに紐づくcategoriesも一緒に読み込み。
        $item = Item::with(['categories','condition','favorites','comments'])->findOrFail($id);
        return view('items.show', compact('item'));
    }
}
