<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class MypageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // 毎回コンディションシーダーを実行
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\ConditionSeeder::class);
        // $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    // 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_mypage_displays()
    {
        // マイページに必要な情報を作成
        $user = User::factory()->create();
        $this->actingAs($user);

        $sell_item = Item::factory()->create([
            'user_id'      => $user->id,
            'name'         => '出品商品',
            'picture'      => 'png/box_danbo-ru_close.png',
        ]);

        $sold_item = Item::factory()->create([
            'user_id'      => User::factory()->create()->id,
            'name'         => '購入商品',
            'picture'      => 'png/box_danbo-ru_close.png',
        ]);

        $profile =Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
            'postal_code' => '999-9999',
            'address' => '東京都品川区',
            'building' => 'マンション101',
        ]);

        $order = Order::factory()->create([
            'item_id' => $sold_item->id,
            'user_id' => $user->id,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
            'payment_method' => 'カード払い',
        ]);

        // マイページ画面表示
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        // 表示確認
        $response->assertSee('png/ダミー用プロフィール画像.png');
        $response->assertSee('ユーザー1');
        $response->assertSee('出品商品');
        $response->assertSee('購入商品');
    }
}
