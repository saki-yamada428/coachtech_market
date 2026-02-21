@extends('layouts.app')
@section('title', '配送先住所変更画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/address/edit.css') }}">
@endsection


@section('content')
<div class="profile-form__content">
    <div class="profile-form__heading">
        <h2>住所の変更</h2>
    </div>
    <!-- フォーム -->
    <form class="form" action="{{ route('address.update', ['id' => $item->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 郵便番号 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postal_code" value="{{ old('postal_code', $item->user->profile->postal_code) }}">
                </div>
                <div class="form__error">
                    @error('postal_code')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 住所 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', $item->user->profile->address) }}">
                </div>
                <div class="form__error">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 建物名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $item->user->profile->building) }}">
                </div>
            </div>
        </div>

        <!-- 更新ボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection
