@extends('layouts.app')
@section('title', 'ログイン画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('header__button')
<nav>
    <ul class="header-nav">
        <li class="header-nav__item">
            <a class="header-nav__link" href="/mypage">マイページ</a>
        </li>
        <li class="header-nav__item">
            <form method="POST" action="/logout">
                @csrf
                <button class="header-nav__button">ログアウト</button>
            </form>
        </li>
        <li class="header-nav__item">
            <button class="header-nav__button_sell">出品</button>
        </li>
    </ul>
</nav>
@endsection

@section('content')
<div class="login-form__content">
    <div class="login-form__heading">
        <h2>ログイン</h2>
    </div>

    <!-- フォーム -->
    <form class="form" action="/login" method="POST">
        @csrf
        <!-- メール -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">メールアドレス</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="email" name="email" value="{{ old('email') }}" />
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- パスワード -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">パスワード</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="password" name="password" />
                </div>
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- ログインボタン -->
        <div class="form__button">
            <button class="form__button-submit" type="submit">ログインする</button>
        </div>
    </form>

    <!-- 登録ページ移動 -->
    <div class="register__link">
        <a class="register__button-submit" href="/register">会員登録はこちら</a>
    </div>
</div>
@endsection
