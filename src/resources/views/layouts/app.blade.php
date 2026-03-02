<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css')  }}" >
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    <img src="{{ asset('png/COACHTECH_header_logo.png') }}" alt="logo" class="logo">
                </a>

                @if (!request()->is('login','register'))
                <nav>
                    <ul class="header-nav">
                        <!-- <li class="header-nav__item"> -->
                        <div class="header-nav__form">
                            <!-- 検索バー -->
                            <form class="header-nav__search" action="{{ route('items.search') }}" method="GET">
                                @csrf
                                <input class="search-form" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                                <button class="search-button" type="submit">検索</button>
                            </form>
                        </div>
                        <!-- </li> -->
                        <div class="header-nav__right">
                            <li class="header-nav__item">
                                <!-- ログインしていない時 -->
                                @guest
                                    <a href="/login" class="header-nav__button">ログイン</a>
                                @endguest

                                <!-- ログインしている時 -->
                                @auth
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button class="header-nav__button">ログアウト</button>
                                    </form>
                                @endauth
                            </li>

                            <li class="header-nav__item">
                                <a class="header-nav__link" href="/mypage">マイページ</a>
                            </li>

                            <li class="header-nav__item">
                                <a class="header-nav__button_sell" href="/sell">出品</a>
                            </li>
                        </div>
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>
