@extends('layouts.app')
@section('title', 'マイページ')
@section('css')
<link rel="stylesheet" href="{{ asset('css/users/mypage.css') }}">
@endsection


@section('content')
<div class="mypage__content">
    <div class="mypage__heading">
        <!-- プロフィール画像 -->
        <div class="heading__picture">
            @if ($profile->picture)
                <!-- 画像が設定済み -->
                <img id="preview" class="profile-image"
                    src="{{ asset('storage/' . $profile->picture) }}"
                    alt="プロフィール画像">
            @else
                <!-- 画像が未設定（デフォルト画像(透明な1×1ピクセルのダミー画像)） -->
                <div class="default-image">
                    <img id="preview" class="profile-image-default"
                    src="{{ asset('storage/png/透明画像.png') }}"
                    alt="デフォルト画像">
                </div>
            @endif
        </div>

        <div class="heading__user-name">
            <p>{{ $profile->nickname }}</p>
        </div>

        <div class="heading__button">
            <a class="profile-edit-button" href="/mypage/profile">プロフィールを編集</a>
        </div>
    </div>

    <!-- 商品一覧 -->
    <div class="index__content">
        <div class="tab">
            <form action="{{ route('items.index') }}" method="GET">
                <input type="hidden" name="favorite" value="1">
                <div class="tab__button">
                    <button class="tab__button-submit">
                        出品した商品
                    </button>
                    <button class="tab__button-submit">
                        購入した商品
                    </button>
            </div>
            </form>
        </div>

        <!-- 商品リスト -->
        <div class="row">
            @forelse ($items as $item)
                <div class="col-md-3 mb-4">
                <!-- 4カラム -->
                    <div class="item">

                        <!-- {{-- 商品画像 --}} -->
                        <a href="{{ route('items.show', $item->id) }}">
                            <img src="{{ asset($item->picture) }}"
                                class="item-image"
                                alt="{{ $item->name }}">
                        </a>
                        <div class="card-body">
                            <!-- {{-- 商品名 --}} -->
                            <a href="{{ route('items.show', $item->id) }}">
                                <h5 class="item-name">{{ $item->name }}</h5>
                            </a>
                            <!-- <p class="card-text">
                                ¥{{ number_format($item->price) }}
                            </p> -->

                            <!-- {{-- <a href="{{ route('items.show', $item->id) }}" class="btn btn-outline-primary w-100"> --}}-->
                            <!-- <p class='test'>
                                詳細を見る
                            </p> -->
                            <!-- {{-- </a> --}}-->
                        </div>
                    </div>
                </div>
            @empty
                <p>商品がありません。</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
