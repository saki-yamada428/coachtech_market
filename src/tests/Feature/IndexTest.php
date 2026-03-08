<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;


class IndexTest extends TestCase
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
    }

    // 商品一覧画面を表示し、商品の情報が表示されている
    public function test_items_are_displayed_on_index_page()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        $this->actingAs($user1);

        $item1 = Item::factory()->create(['name' => '商品A','user_id' => $user2->id]);
        $item2 = Item::factory()->create(['name' => '商品B','user_id' => $user2->id]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    // 購入済み商品にSoldと表示
    public function test_sold_label_is_displayed_for_items_with_orders()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        $this->actingAs($user1);

        // 購入済み商品
        $soldItem = Item::factory()->create([
            'name' => '売れた商品',
            'user_id' => $user2->id,
        ]);

        // Orders にレコードを作成（購入済み）
        Order::factory()->create([
            'item_id' => $soldItem->id,
            'user_id' => User::factory()->create()->id,
        ]);

        // 未購入商品
        $unsoldItem = Item::factory()->create([
            'name' => '未購入の商品',
            'user_id' => $user2->id,
        ]);

        $response = $this->get('/');

        // 購入済み商品には Sold が表示される
        $response->assertSee('Sold');
        $response->assertSee('売れた商品');

        // 未購入商品には Sold が表示されない
        $response->assertSee('未購入の商品');

        // soldの表示回数が１回
        $this->assertEquals(1, substr_count($response->getContent(), 'Sold'));
    }

    // 自分が出品した商品が表示されない
    public function test_my_own_items_are_not_displayed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 自分の商品
        $myItem = Item::factory()->create([
            'name' => '自分の商品',
            'user_id' => $user->id,
        ]);

        // 他人の商品
        $otherItem = Item::factory()->create([
            'name' => '他人の商品',
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get('/');

        // 自分の商品は見えない
        $response->assertDontSee('自分の商品');

        // 他人の商品は見える
        $response->assertSee('他人の商品');
    }
}
