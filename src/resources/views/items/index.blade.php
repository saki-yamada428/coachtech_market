@extends('layouts.app')
@section('title', '商品一覧画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection


@section('content')
<div class="index__content">
    <div class="tab">
        <form action="{{ route('items.index') }}" method="GET">
            <input type="hidden" name="favorite" value="1">
            <div class="tab__button">
                <button class="tab__button-submit">
                    おすすめ
                </button>
                <button class="tab__button-submit">
                    マイリスト
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
@endsection
