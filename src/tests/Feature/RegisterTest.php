<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    // 会員登録画面の表示
    public function test_register_page_can_be_displayed()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    // 名前が空の場合
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    // メールが空の場合
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレス を入力してください',
        ]);
    }

    // パスワードが空の場合
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワード を入力してください',
        ]);
    }

    // パスワードが７文字以下の場合
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワード は 8 文字以上で入力してください',
        ]);
    }

    // パスワードが確認用と異なる場合
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワード と一致しません',
        ]);
    }

    // 正しく入力するとUsersに登録されプロフィール変更画面に遷移
    public function test_user_can_register_and_redirect_to_profile_edit()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DB にユーザーが作成されている
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // 認証されている
        $this->assertAuthenticated();

        // プロフィール編集画面へリダイレクト
        $response->assertRedirect('/mypage/profile');
    }
}
