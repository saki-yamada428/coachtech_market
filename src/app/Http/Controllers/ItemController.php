<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use App\Models\Profile;
use App\Models\Order;
use App\Models\Comment;

// バリデーション
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\CommentRequest;

// Stripe
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ItemController extends Controller
{
    // 一覧画面表示
    public function index()
    {
        // 全商品を取得（自分が出品した商品を除く）
        $items = Item::with('order')->get();

        // お気に入り商品取得
        $favoriteItems = Item::whereHas('favoredBy', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        // $favoriteItems = Item::with('favorites')

        return view('items.index', compact('items','favoriteItems'));
    }

    // 商品検索機能
    public function search(Request $request)
    {
        // 全商品を取得
        $query = Item::with('order');

        // キーワード一致した商品に絞り込み
        if ($request->filled('keyword')) {
            $query->where('name', 'like', "%{$request->keyword}%");
        }

        // キーワード一致した商品をitemsに入れる
        $items = $query->get();

        // お気に入り商品取得
        $favoriteItems = Item::whereHas('favoredBy', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        // $favoriteItems = Item::with('favorites')

        return view('items.index', compact('items','favoriteItems'));
    }

    // 商品詳細画面表示
    public function show($id)
    {
        // ID が一致するItemを取得、Itemに紐づくcategoriesも一緒に読み込み。
        $item = Item::with(['categories','condition','favoredBy','comments'])->findOrFail($id);
        return view('items.show', compact('item'));
    }

    // コメント送信
    public function comments(CommentRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'comment' => $request->comment,
        ]);
        return back();
    }


    // 出品画面表示
    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.sell', compact('categories', 'conditions'));
    }

    // 出品機能
    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        // 画像保存
        $path = $request->file('picture')->store('png', 'public');
        // 商品作成
        $item = Item::create([
            'user_id'      => auth()->id(),
            'name'         => $validated['name'],
            'picture'      => $path,
            'brand'        => $validated['brand'] ?? null,
            'price'        => $validated['price'],
            'description'  => $validated['description'],
            'condition_id' => $validated['condition_id'],
        ]);

        // カテゴリー紐付け（多対多の場合）
        $item->categories()->sync($request->category_id);

        return redirect()->route('mypage');
    }

    // 商品購入画面表示
    public function purchase($id)
    {
        // ID が一致するItemを取得、Itemに紐づくcategoriesも一緒に読み込み。
        $item = Item::with('user.profile')->findOrFail($id);
        // ログインユーザー
        $user = auth()->user();
        // ログインユーザーに紐づくプロフィール
        $profile = $user->profile;

        return view('items.purchase', compact('item','user','profile'));
    }

    // 購入ボタン
    public function sold(PurchaseRequest $request, $id)
    {
        $item = Item::findOrFail($id);

        // Order::create([
        //     'item_id' => $item->id,
        //     'user_id' => auth()->id(),
        //     'postal_code' => $request->postal_code,
        //     'address' => $request->address,
        //     'building' => $request->building,
        //     'payment_method' => $request->payment_method,
        // ]);
        // return redirect()->route('items.index');

        // 応用（Stripe決済画面遷移）
        // 住所などは success で使うので session に保存
        session([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'payment_method' => $request->payment_method,
        ]);

        // Stripe の秘密鍵をセット
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Stripe Checkout Session を作成
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                        'description' => $item->description ?? '商品説明なし',
                    ],
                    'unit_amount' => (int)$item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['id' => $item->id]),
            'cancel_url' => route('items.purchase', ['id' => $item->id]),
        ]);

        // Stripe の決済画面へリダイレクト
        return redirect($session->url);
    }

    // Stripe決済成功後
    public function success(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        Order::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'postal_code' => session('postal_code'),
            'address' => session('address'),
            'building' => session('building'),
            'payment_method' => session('payment_method'),
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
