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

class FavoriteTest extends TestCase
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

    // ハートアイコン押下でfavoriteに追加、いいね数増加、アイコンの色変化
    public function test_user_can_favorite_item_and_ui_updates()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'お気に入り商品',
            'user_id' => User::factory()->create()->id,
        ]);

        // 初期状態：お気に入り数 0
        $response = $this->get('/items/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('<span class="fav-count">0</span>', false); // 初期のお気に入り数
        $response->assertSee('/png/ハートロゴ_デフォルト.png');

        // ハートアイコンを押す（POST）
        $this->post('/items/' . $item->id);

        // DB にレコードが追加されたか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 再度ページを表示
        $response = $this->get('/items/' . $item->id);

        // お気に入り数が 1 に増えている
        $response->assertSee('<span class="fav-count">1</span>', false);

        // ハートアイコンの色が変わる
        $response->assertSee('/png/ハートロゴ_ピンク.png');
    }

    // 再度ハートアイコン押下でいいね解除
    public function test_user_can_unfavorite_item_and_ui_updates()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'name' => 'お気に入り商品',
            'user_id' => User::factory()->create()->id,
        ]);

        // いいねしてある状態にする
        $user->favoriteItems()->attach($item->id);

        // 初期状態：いいね済み
        $response = $this->get('/items/' . $item->id);
        $response->assertStatus(200);
        $response->assertSee('<span class="fav-count">1</span>', false); // 初期のお気に入り数
        $response->assertSee('/png/ハートロゴ_ピンク.png');

        // いいね解除
        $this->delete('/items/' . $item->id);

        // 再度ページを表示
        $response = $this->get('/items/' . $item->id);

        // お気に入り数が 0 に減っている
        $response->assertSee('<span class="fav-count">0</span>', false);

        // ハートアイコンの色が変わる
        $response->assertSee('/png/ハートロゴ_デフォルト.png');
    }
}
