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
            <div class="tab__button">
                <button class="tab__button-submit active" type="button" onclick="showTab('sell')">
                <!-- 最初は出品をアクティブ -->
                    出品した商品
                </button>
                <button class="tab__button-submit" type="button" onclick="showTab('order')">
                    購入した商品
                </button>
            </div>
        </div>

        <!-- 商品リスト -->
        <!-- 出品した商品 -->
        <div class="tab-sell" id="tab-sell">
            <div class="row">
                @forelse ($items as $item)
                    @if ($item->user_id == auth()->id())
                        <div class="col-md-3 mb-4">
                        <!-- 4カラム -->
                            <div class="item">

                                <!-- {{-- 商品画像 --}} -->
                                <a href="{{ route('items.show', $item->id) }}">
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </a>
                                <div class="card-body">
                                    <!-- {{-- 商品名 --}} -->
                                    <a href="{{ route('items.show', $item->id) }}">
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
                    <p>商品がありません。</p>
                @endforelse
            </div>
        </div>

        <!-- 購入した商品 -->
        <div class="tab-order" id="tab-order" style="display:none;">
            <div class="row">
                @forelse ($items as $item)
                    @if ($item->order && $item->order->user_id == auth()->id())
                        <div class="col-md-3 mb-4">
                        <!-- 4カラム -->
                            <div class="item">

                                <!-- {{-- 商品画像 --}} -->
                                <a href="{{ route('items.show', $item->id) }}">
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </a>
                                <div class="card-body">
                                    <!-- {{-- 商品名 --}} -->
                                    <a href="{{ route('items.show', $item->id) }}">
                                        <h5 class="item-name">{{ $item->name }}</h5>
                                    </a>
                                    <!-- 購入済み -->
                                    <span class="sold">Sold</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p>商品がありません。</p>
                @endforelse
            </div>
        </div>

    <!-- タブ切り替え -->
    <script>
    function showTab(name) {
        // タブの中身を切り替え
        document.getElementById('tab-sell').style.display = 'none';
        document.getElementById('tab-order').style.display = 'none';
        document.getElementById('tab-' + name).style.display = 'block';

        // ボタンの active を切り替え
        document.querySelectorAll('.tab__button-submit').forEach(btn => {
            btn.classList.remove('active');
        });

        // 押されたボタンに active を付ける
        document.querySelector(`button[onclick="showTab('${name}')"]`)
            .classList.add('active');
    }
    </script>

        <!-- <div class="mt-4">
            {{-- $items->links() --}}
        </div> -->
    </div>
</div>
@endsection
