@extends('layouts.app')
@section('title', '商品詳細画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection


@section('content')
<div class="item__detail">

    <!-- {{-- 商品画像 --}} -->
    <div class="item__detail-picture">
        <img src="{{ $item->picture_url }}"
            class="item-picture"
            alt="{{ $item->name }}">
    </div>

    <!-- {{-- 商品説明 --}} -->
    <div class="item__detail-info">
        <!-- {{-- 商品名 --}} -->
        <h1 class="item__detail-name">{{ $item->name }}</h1>

        <!-- {{-- ブランド名 --}} -->
        <div class="item__detail-brand">{{ $item->brand}}</div>

        <!-- {{-- 価格 --}} -->
        <div class="item__detail-price">
            ¥{{ number_format($item->price) }}
            <p class="tax">(税込)</p>
        </div>

        <!-- {{-- お気に入り,コメントアイコン --}} -->
        <div class="item__detail-logo">
            <div class="item__detail-logo-heart">
                @if ($item->favoredBy->contains(auth()->id()))
                    <form action="{{ route('favorite.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <!-- <button type="submit" class="logo-heart">
                            <img src="/png/ハートロゴ_ピンク.png" alt="お気に入り解除">
                        </button> -->
                        <input class="logo-heart" type="image" src="/png/ハートロゴ_ピンク.png">
                    </form>
                @else
                    <form action="{{ route('favorite.store', $item->id) }}" method="POST">
                        @csrf
                        <!-- <button type="submit" class="logo-heart">
                            <img src="/png/ハートロゴ_デフォルト.png" alt="お気に入り">
                        </button> -->
                        <input class="logo-heart" type="image" src="/png/ハートロゴ_デフォルト.png">
                    </form>
                @endif
                <span>{{ $item->favoredBy->count() }}</span>
            </div>
            <div class="item__detail-logo-hukidasi">
                <img src="/png/ふきだしロゴ.png"
                    class="logo-hukidasi">
                <span>{{ $item->comments->count() }}</span>
            </div>
        </div>

        <!-- {{-- 購入手続きボタン --}} -->
        <form action="{{ route('items.purchase', $item->id) }}" method="GET">
            <button class="item__detail-button">購入手続きへ</button>
        </form>

        <!-- {{-- 説明文 --}} -->
        <div class="item__detail-description">
            <h2 class="item__detail-description-title">
                商品説明
            </h2>
            {{ $item->description }}
        </div>

        <!-- {{-- カテゴリー＆状態 --}} -->
        <div class="item__detail-type">
            <h2 class="item__detail-info-title">
                商品の情報
            </h2>
            <div class="item__detail-category">
                カテゴリー
                <div class="category">
                    @foreach ($item->categories as $category)
                    <li class="category-name">{{ $category->name }}</li>
                    @endforeach
                </div>
            </div>
            <div class="item__detail-condition">
                商品の状態
                <div class="condition-name">
                    {{ $item->condition->name }}
                </div>
            </div>
        </div>

        <!-- {{-- コメント --}} -->
        <div class="item__detail-commented">
            <h2 class="item__detail-commented-title">
                コメント({{ $item->comments->count() }})
            </h2>
            <div class="commented-user">
                <!-- １件のみ表示 -->
                <!-- @if ($item->comments->first()?->user?->profile)
                    <img class="commented-user-picture"
                        src="{{ asset($item->comments->first()->user->profile->picture) }}"
                        alt="プロフィール画像">
                    {{ $item->comments->first()->user->profile->nickname }}
                @endif
                @if ($item->comments->first()?->comment)
                <div class="commented">
                    {{ $item->comments->first()->comment }}
                </div>
                @endif -->
                <!-- 全件表示 -->
                @foreach ($item->comments as $comment)
                    @if ($comment->user?->profile)
                        <img class="commented-user-picture"
                        src="{{ asset('storage/' . $comment->user->profile->picture) }}"
                        alt="プロフィール画像">
                        {{ $comment->user->profile->nickname }}
                    @endif
                    <div class="commented">
                        {{ $comment->comment }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- {{-- コメント送信フォーム --}} -->
        <form class="item__detail-comment" action="{{ route('items.comments', $item->id) }}" method="POST">
            @csrf
            <h3 class="item__detail-comment-title">
                商品へのコメント
            </h3>
            <div class="form__error">
                @error('comment')
                {{ $message }}
                @enderror
            </div>
            <textarea name="comment" class="comment-body"></textarea>
            <button class="comment-button">
                コメントを送信する
            </button>
        </form>
    </div>
</div>
@endsection
