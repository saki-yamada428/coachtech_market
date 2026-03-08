@extends('layouts.app')
@section('title', 'メール認証画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/email.css') }}">
@endsection


@section('content')
<div class="verify-email">
    <p>登録したメールアドレスに認証リンクを送信しました。</p>
    <p>メール内認証を完了してください。</p>

    @if (session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif

    <a class="mailtrap" href="https://mailtrap.io/inboxes" target="_blank">
        認証はこちらから
    </a>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="resend-mail" type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection
