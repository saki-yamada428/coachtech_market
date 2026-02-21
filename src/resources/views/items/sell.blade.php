@extends('layouts.app')
@section('title', '商品出品画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection


@section('content')
<div class="sell-form__content">
    <div class="sell__title">
        <h1>商品の出品</h1>
    </div>

    <!-- フォーム -->
    <form class="form" action="{{ route('sell') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 商品画像 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品画像</span>
            </div>
            <div class="form__group-picture">
                <!-- {{-- 画像変更ボタン --}} -->
                <button type="button" class="change-picture-btn"
                    onclick="document.getElementById('picture-input').click()">
                    画像を選択する
                </button>

                <!-- {{-- 実際の file input（非表示） --}} -->
                <input id="picture-input" type="file" name="picture"
                    style="display:none;">

                <div class="form__error">
                    @error('picture')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <!-- プレビュー用 JavaScript -->
        <script>
        document.getElementById('picture-input').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
        </script>

        <!-- 商品の詳細 -->
        <div class="sell-detail__title">
            <h2>商品の詳細</h2>
        </div>

        <!-- カテゴリー -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">カテゴリー</span>
            </div>
            <div class="category-list">
                @foreach ($categories as $category)
                    <label class="category-item">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 商品の状態 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の状態</span>
            </div>

            <div class="select">
                <select class="condition" name="condition">
                    <option hidden>選択してください</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->name }}">{{ $condition->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                @error('address')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="sell-detail__title">
            <h2>商品名と説明</h2>
        </div>

        <!-- ブランド名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ブランド名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="brand">
                </div>
                <div class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 商品の説明 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">商品の説明</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <textarea name="description"></textarea>
                </div>
                <div class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 販売価格 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">販売価格</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--price">
                    <input type="number" name="price">
                </div>
                <div class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 出品ボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">出品する</button>
        </div>
    </form>
</div>
@endsection
