<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
// use App\Models\Order;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // 毎回コンディションシーダーとカテゴリーシーダーを実行
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    // ログイン済みのユーザーはコメント可能
    public function test_logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();
        // $userがログイン
        $this->actingAs($user);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->post('/items/' . $item->id . '/comments', [
            'comment' => 'テストコメント',
        ]);

        // 正常にコメントが保存されているか
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        // リダイレクト先が商品詳細ページ
        $response->assertRedirect('/items/' . $item->id);
    }

    // ログイン前のユーザーはコメント不可
    public function test_guest_user_cannot_post_comment()
    {
        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->post('/items/' . $item->id . '/comments', [
            'comment' => 'ゲストコメント',
        ]);

        // ログイン画面へリダイレクト
        $response->assertRedirect('/login');

        // DB に保存されていない
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストコメント',
        ]);
    }

    // コメントが未入力の場合バリデーションメッセージ
    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->post('/items/' . $item->id . '/comments', [
            'comment' => '',
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors(['comment']);
    }

    // コメントが256文字以上の場合バリデーションメッセージ
    public function test_comment_must_be_less_than_256_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
        ]);

        $item = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $longComment = str_repeat('あ', 256); // 256文字

        $response = $this->post('/items/' . $item->id . '/comments', [
            'comment' => $longComment,
        ]);

        // バリデーションエラー
        $response->assertSessionHasErrors(['comment']);
    }
}
