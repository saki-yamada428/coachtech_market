<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;
// use Illuminate\Support\Facades\DB;

class MylistTest extends TestCase
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

    // いいねした商品だけが表示される
    public function test_mylist_are_displayed_on_index_page()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        $this->actingAs($user1);

        $item1 = Item::factory()->create(['name' => '商品A','user_id' => $user2->id]);
        $item2 = Item::factory()->create(['name' => '商品B','user_id' => $user2->id]);

        // 商品Aにだけいいねする
        $user1->favoriteItems()->attach($item1->id);
        // DB::table('favorites')->insert([
        //     'user_id' => $user1->id,
        //     'item_id' => $item1->id,
        // ]);

        $response = $this->get('/search?tab=mylist');

        $mylistHtml = $this->extractMylistHtml($response);

        // 正常にページが表示されたか判定
        $response->assertStatus(200);

        // 商品Aだけ表示され商品Bは表示されない
        $this->assertStringContainsString('商品A', $mylistHtml);
        $this->assertStringNotContainsString('商品B', $mylistHtml);

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

        // 両方の商品にいいねする
        $user1->favoriteItems()->attach($soldItem->id);
        $user1->favoriteItems()->attach($unsoldItem->id);

        // DB::table('favorites')->insert([
        //     'user_id' => $user1->id,
        //     'item_id' => $soldItem->id,
        // ]);
        // DB::table('favorites')->insert([
        //     'user_id' => $user1->id,
        //     'item_id' => $unsoldItem->id,
        // ]);

        $response = $this->get('/search?tab=mylist');

        $mylistHtml = $this->extractMylistHtml($response);

        // 購入済み商品には Sold が表示される
        $this->assertStringContainsString('Sold', $mylistHtml);
        $this->assertStringContainsString('売れた商品', $mylistHtml);

        // 未購入商品には Sold が表示されない
        $this->assertStringContainsString('未購入の商品', $mylistHtml);

        // soldの表示回数が１回
        $this->assertEquals(1, substr_count($mylistHtml, 'Sold'));
    }

    // 未認証の場合何も表示されない
    public function test_not_auth_user_are_not_displayed()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        // $this->actingAs($user1);

        $item1 = Item::factory()->create(['name' => '商品A','user_id' => '2']);
        $item2 = Item::factory()->create(['name' => '商品B','user_id' => '2']);

        // 商品Aにいいねする
        $user1->favoriteItems()->attach($item1->id);
        // DB::table('favorites')->insert([
        //     'user_id' => $user1->id,
        //     'item_id' => $item1->id,
        // ]);

        $response = $this->get('/search?tab=mylist');

        // 後述のextractMylistHtmlメソッド使用
        $mylistHtml = $this->extractMylistHtml($response);

        // 商品Aが表示されない
        $this->assertStringNotContainsString('商品A', $mylistHtml);
    }

    // extractMylistHtmlメソッドの定義（マイリストタブだけ対象にする）
    private function extractMylistHtml($response)
    {
        $html = $response->getContent();

        // mylist タブの開始位置
        $start = strpos($html, '<div class="tab-favorite"');

        if ($start === false) {
            return '';
        };

        // 開始タグの位置から HTML を切り出す
        $sub = substr($html, $start);

        $openDiv = 0;
        $pos = 0;

        while (true) {
            // 次の <div と </div> を探す
            $nextOpen  = strpos($sub, '<div', $pos);
            $nextClose = strpos($sub, '</div>', $pos);

            // 最初のタグが <div> の場合
            if ($nextOpen !== false && $nextOpen < $nextClose) {
                $openDiv++;
                $pos = $nextOpen + 4;
            }
            // 最初のタグが </div> の場合
            else {
                $openDiv--;
                $pos = $nextClose + 6;

                // 開始タグと閉じタグのバランスが取れたら終了
                if ($openDiv < 0) {
                    return substr($sub, 0, $pos);
                }
            }
        }
    }
}
