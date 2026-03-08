@extends('layouts.app')
@section('title', '商品一覧画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection


@section('content')
<div class="index__content">
    <div class="tab">
        <!-- <input type="hidden" name="favorite" value="1"> -->
        <div class="tab__button">
            <a href="{{ route('items.search', ['keyword' => request('keyword'), 'tab' => 'all']) }}"
                class="tab__button-submit {{ $tab === 'all' ? 'active' : '' }}">
                <!-- 最初はおすすめをアクティブ -->
                おすすめ
            </a>
            <a href="{{ route('items.search', ['keyword' => request('keyword'), 'tab' => 'mylist']) }}"
                class="tab__button-submit {{ $tab === 'mylist' ? 'active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <!-- 商品リスト -->
    <!-- おすすめ -->
    <div class="tab-all" id="tab-all" style="{{ $tab === 'all' ? '' : 'display:none;' }}">
        <div class="row">
            @forelse ($items as $item)
                @if ($item->user_id !== auth()->id())
                    <div class="col-md-3 mb-4">
                    <!-- 4カラム -->
                        <div class="item">
                            @if ($item->order)
                            <!-- 購入済み -->
                                <!-- {{-- 商品画像 --}} -->
                                <div class="sold-item">
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </div>

                                <!-- {{-- 商品名 --}} -->
                                <div class="card-body">
                                    <a class="sold-item">
                                        <span class="item-name">{{ $item->name }}</span>
                                    </a>
                                        <span class="sold">Sold</span>
                                </div>
                            @else
                            <!-- 販売中 -->
                                <!-- {{-- 商品画像 --}} -->
                                <a href="{{ route('items.show', $item->id) }}">
                                    <img src="{{ $item->picture_url }}"
                                        class="item-image"
                                        alt="{{ $item->name }}">
                                </a>

                                <!-- {{-- 商品名 --}} -->
                                <div class="card-body">
                                    <a href="{{ route('items.show', $item->id) }}">
                                        <span class="item-name">{{ $item->name }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @empty
                <p></p>
            @endforelse
        </div>
    </div>

    <!-- マイリスト -->
    <div class="tab-favorite" id="tab-favorite" style="{{ $tab === 'mylist' ? '' : 'display:none;' }}">
        <div class="row">
        @forelse ($favoriteItems as $item)
            <div class="col-md-3 mb-4">
            <!-- 4カラム -->
                <div class="item">
                    @if ($item->order)
                    <!-- 購入済み -->
                        <!-- {{-- 商品画像 --}} -->
                        <div class="sold-item">
                            <img src="{{ $item->picture_url }}"
                                class="item-image"
                                alt="{{ $item->name }}">
                        </div>

                        <!-- {{-- 商品名 --}} -->
                        <div class="card-body">
                            <a class="sold-item">
                                <span class="item-name">{{ $item->name }}</span>
                            </a>
                                <span class="sold">Sold</span>
                        </div>
                    @else
                    <!-- 販売中 -->
                        <!-- {{-- 商品画像 --}} -->
                        <a href="{{ route('items.show', $item->id) }}">
                            <img src="{{ $item->picture_url }}"
                                class="item-image"
                                alt="{{ $item->name }}">
                        </a>

                        <!-- {{-- 商品名 --}} -->
                        <div class="card-body">
                            <a href="{{ route('items.show', $item->id) }}">
                                <span class="item-name">{{ $item->name }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p></p>
        @endforelse
        </div>
    </div>
</div>
@endsection
