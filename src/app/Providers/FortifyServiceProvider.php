<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

use App\Actions\Fortify\LoginResponse;
use App\Actions\Fortify\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse;

// バリデーション
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ログアウト後の遷移先
        $this->app->singleton(
            LogoutResponseContract::class, LogoutResponse::class);

        // ログイン後
        $this->app->singleton(
            LoginResponseContract::class, LoginResponse::class);

        // 新規登録後
        $this->app->singleton(
            RegisterResponseContract::class, RegisterResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // バリデーション
        Fortify::authenticateUsing(function (Request $request) {

            // FormRequest を手動で実行
            app(LoginRequest::class)->validateResolved();

            // 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // 新規登録処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // ▼ 削除：プロフィール更新・パスワード変更・リセット・2FA を使わない
        // Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        // Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        // Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        // Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // emailを取り出しIPアドレスと結合
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())).'|'.$request->ip()
                );
            return Limit::perMinute(10)->by($throttleKey);
        });

        // RateLimiter::for('two-factor', function (Request $request) {
        //     return Limit::perMinute(5)->by($request->session()->get('login.id'));
        // });

        // ▼ 追加：Blade のログイン・登録画面を使う設定
        // ログイン後のリダイレクト先
        Fortify::redirects('login', '/');

        // ログイン画面の表示
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 新規登録画面の表示（Web.phpの代わり）
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // 新規登録後のリダイレクト先
        Fortify::redirects('register','/mypage/profile');

    }
}
