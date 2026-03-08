@extends('layouts.app')
@section('title', 'プロフィール画面')
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
            <div class="tab__button">
                <a href="/mypage?tab=sell" class="tab__button-submit {{ $tab === 'sell' ? 'active' : '' }}">
                <!-- 最初は出品をアクティブ -->
                    出品した商品
                </a>
                <a href="/mypage?tab=order" class="tab__button-submit {{ $tab === 'order' ? 'active' : '' }}">
                    購入した商品
                </a>
            </div>
        </div>

        <!-- 商品リスト -->
        <!-- 出品した商品 -->
        <div class="tab-sell" id="tab-sell" style="{{ $tab === 'sell' ? '' : 'display:none;' }}">
            <div class="row">
                @forelse ($items as $item)
                    @if ($item->user_id == auth()->id())
                        <div class="col-md-3 mb-4">
                        <!-- 4カラム -->
                            <div class="item">

                                <!-- {{-- 商品画像 --}} -->
                                <a>
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </a>
                                <div class="card-body">
                                    <!-- {{-- 商品名 --}} -->
                                    <a>
                                        <span class="item-name">{{ $item->name }}</span>
                                    </a>
                                    <!-- 購入済み -->
                                    @if ($item->order)
                                        <span class="sold">Sold</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p></p>
                @endforelse
            </div>
        </div>

        <!-- 購入した商品 -->
        <div class="tab-order" id="tab-order" style="{{ $tab === 'order' ? '' : 'display:none;' }}">
            <div class="row">
                @forelse ($items as $item)
                    @if ($item->order && $item->order->user_id == auth()->id())
                        <div class="col-md-3 mb-4">
                        <!-- 4カラム -->
                            <div class="item">

                                <!-- {{-- 商品画像 --}} -->
                                <a>
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </a>
                                <div class="card-body">
                                    <!-- {{-- 商品名 --}} -->
                                    <a>
                                        <span class="item-name">{{ $item->name }}</span>
                                    </a>
                                    <!-- 購入済み -->
                                    <span class="sold">Sold</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p></p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
