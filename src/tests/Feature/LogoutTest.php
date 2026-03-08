<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    // ログイン後、ログアウトしたらログインページを表示
    public function test_user_can_logout()
    {
        // まずログイン状態を作る
        $user = User::factory()->create();

        $this->actingAs($user);

        // ログアウトリクエスト
        $response = $this->post('/logout');

        // 認証状態が解除されている
        $this->assertGuest();

        // リダイレクト先の確認
        $response->assertRedirect('/login');
    }
}
