@extends('layouts.app')
@section('title', 'プロフィール編集画面')
@section('css')
<link rel="stylesheet" href="{{ asset('css/users/profile.css') }}">
@endsection


@section('content')
<div class="profile-form__content">
    @if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif

    <div class="profile-form__heading">
        <h2>プロフィール設定</h2>
    </div>
    <!-- フォーム -->
    <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- プロフィール画像 -->
        <div class="form__group">
            <div class="form__input--picture">
                @if ($profile->picture)
                    <!-- 画像が設定済み -->
                    <img id="preview" class="profile-image"
                        src="{{ asset('storage/' . $profile->picture) }}"
                        alt="プロフィール画像">
                @else
                    <!-- 画像が未設定（デフォルト画像(透明なダミー画像)） -->
                    <div class="default-image">
                        <img id="preview" class="profile-image-default"
                            src="{{ asset('storage/png/透明画像.png') }}"
                            alt="デフォルト画像">
                    </div>
                @endif

                <!-- {{-- 画像変更ボタン --}} -->
                <button type="button" class="change-picture-btn"
                    onclick="document.getElementById('picture-input').click()">
                    画像を選択する
                </button>

                <!-- {{-- 実際の file input（非表示） --}} -->
                <input id="picture-input" type="file" name="picture" hidden>

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

        <!-- ユーザー名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="nickname" value="{{ old('nickname', $profile->nickname) }}">
                </div>
                <div class="form__error">
                    @error('nickname')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <!-- 郵便番号 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}">
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
                    <input type="text" name="address" value="{{ old('address', $profile->address) }}">
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
                    <input type="text" name="building" value="{{ old('building', $profile->building) }}">
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
