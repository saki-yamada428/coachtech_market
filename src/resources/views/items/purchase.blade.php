@extends('layouts.app')
@section('title', '商品購入画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
@endsection


@section('content')
<div class="item__purchase">
    <form class="form"  method="POST">
    <!-- <form class="form" action="{{-- route('item.sold', $item->id) --}}" method="POST"> -->
        @csrf
        <input type="hidden" name="user_id" value="{{ $item->user->id }}">
        <input type="hidden" name="item_id" value="{{ $item->id }}">

        <!-- 左側 -->
        <div class="item__purchase__left">

            <!-- 上段 -->
            <div class="item__purchase__title">
                <!-- {{-- 商品画像 --}} -->
                <div class="item__detail-picture">
                    <img src="{{ $item->picture_url }}"
                        class="item-picture"
                        alt="{{ $item->name }}">
                </div>

                <!-- 商品タイトル -->
                <div class="item__title">
                    <!-- {{-- 商品名 --}} -->
                    <h1 class="item__detail-name">{{ $item->name }}</h1>

                    <!-- {{-- 価格 --}} -->
                    <div class="item__detail-price">
                        ¥{{ number_format($item->price) }}
                        <!-- <p class="tax">(税込)</p> -->
                    </div>
                </div>
            </div>

            <!-- 中段ー支払い方法 -->
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">支払い方法</span>
                </div>

                <div class="select">
                    <select class="payment_method" name="payment_method" id="payment_method">
                        <option value="" hidden>選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード払い">カード払い</option>
                    </select>
                    <div class="form__error">
                        @error('payment_method')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 下段ー配送先 -->
            <div class="form__group">
                <div class="shipping-address__title">
                    <div class="form__group-title">
                        <span class="form__label--item">配送先</span>
                    </div>

                    <!-- 変更ボタン -->
                    <a class="address-change-button"
                        href="{{ url('/purchase/address/' . $item->id) }}">
                        変更する
                    </a>
                </div>

                <!-- 郵便番号 -->
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="hidden" name="postal_code"
                            value="{{ session('postal_code', $item->user->profile->postal_code) }}"
                            readonly>
                            {{ session('postal_code', $profile->postal_code) }}
                    </div>
                </div>

                <!-- 住所 -->
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="hidden" name="address"
                            value="{{ session('address', $item->user->profile->address) }}"
                            readonly>
                            {{ session('address', $profile->address) }}
                    </div>
                </div>

                <!-- 建物名 -->
                <div class="form__group-content">
                    <div class="form__input--text">
                        {{ session('building', $profile->building) }}
                        <input type="hidden" name="building"
                            value="{{ session('building', $item->user->profile->building) }}"
                            readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右側 -->
        <div class="item__purchase__right">
            <div class="purchase-check">
                <!-- {{-- 価格 --}} -->
                <div class="price-check">
                    <p class="check-title">商品代金</p>
                    <div class="check-content">
                        ¥{{ number_format($item->price) }}
                        <!-- <p class="tax">(税込)</p> -->
                    </div>
                </div>
                <!-- 支払い方法 -->
                <div class="payment-check">
                    <p class="check-title">支払い方法</p>
                    <p class="check-content"><span id="paymentSummary">未選択</span></p>
                </div>
                <!-- リアルタイム反映 -->
                <script>
                document.getElementById('payment_method')
                    .addEventListener('change',
                    function() {
                        const selected = this.value;
                        document.getElementById('paymentSummary')
                            .textContent = selected || '未選択';
                    });
                </script>
            </div>

            <!-- 購入ボタン -->
            <div class="form__button">
                <button class="form__button-submit">購入する</button>
            </div>
        </div>

    </form>
</div>
@endsection
