<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;

class ItemController extends Controller
{
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

    // 出品画面表示
    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.sell', compact('categories', 'conditions'));
    }

    // 出品機能
    public function store()
    {
        // バリデーション
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'categories' => 'array',
        ]);

        // 商品作成
        $item = Item::create($validated);

        // カテゴリー紐付け（多対多の場合）
        if ($request->categories) {
            $item->categories()->sync($request->categories);
        }

        return redirect()->route('items.index');

    }

    // 商品購入画面表示
    public function purchase($id)
    {
        // ID が一致するItemを取得、Itemに紐づくcategoriesも一緒に読み込み。
        $item = Item::with('user.profile')->findOrFail($id);

        return view('items.purchase', compact('item'));
    }
    // 購入ボタン
    public function sold(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        Purchase::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('items.index');
    }

    // 配送先住所変更画面
    public function edit($id)
    {
        $item = Item::with('user.profile')->findOrFail($id);

        return view('address.edit', compact('item'));
    }
    // 配送先住所変更ボタン
    public function update(Request $request, $id)
    {
        // ページAのフォーム値をセッションに保存
        session([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('items.purchase', ['id' => $id]);
    }
}
