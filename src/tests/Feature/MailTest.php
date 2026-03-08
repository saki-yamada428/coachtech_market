<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class MailTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // 登録したメールアドレス宛に認証メールが送信される
    public function test_send_mail()
    {
        // 実際にメールは送らず通知の送信を記録だけする
        Notification::fake();

        // ユーザー登録
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = \App\Models\User::where('email', 'test@example.com')->first();

        // 指定したユーザーにVerifyEmailが送られた確認
        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    // メール認証画面から、認証はこちらからボタン押下でMailtrap遷移
    public function test_button_redirect_to_mailtrap()
    {
        // 認証前のユーザー作成
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        // ログイン
        $this->actingAs($user);

        // メール認証画面表示
        $response = $this->get('/email/verify');

        $response->assertStatus(200);
        // MailtrapのURLが含まれているか確認
        $response->assertSee('https://mailtrap.io/inboxes');
        $response->assertSee('認証はこちらから');
    }

    // メール認証完了後プロフィール設定画面に遷移
    public function test_mail_verify_success_redirect_to_profile_edit()
    {
        // 認証前のユーザー作成
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 署名付きURLをテスト用に作成（認証リンクと同じ役割）
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // ログインユーザーで上記のURLを踏む
        $response = $this->actingAs($user)->get($url);

        // プロフィール設定画面にリダイレクト
        $response->assertRedirect('/mypage/profile');
        // ユーザーが認証済みになっている
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
