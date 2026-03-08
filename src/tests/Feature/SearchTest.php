<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class SearchTest extends TestCase
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

    // 商品名で部分一致検索
    public function test_items_search_on_index_page()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        $this->actingAs($user1);

        $item1 = Item::factory()->create(['name' => '商品A','user_id' => $user2->id]);
        $item2 = Item::factory()->create(['name' => '商品B','user_id' => $user2->id]);

        // 商品一覧
        $response = $this->get('/');

        // 初期状態（全表示）
        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');

        // 検索
        $response = $this->get('/search?keyword=A');

        // 検索後
        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
    }

    // 検索結果がマイリストでも保持されている
    public function test_items_search_on_mylist_tab()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        // user1をログインユーザーとして扱う
        $this->actingAs($user1);

        $item1 = Item::factory()->create(['name' => '商品A','user_id' => $user2->id]);
        $item2 = Item::factory()->create(['name' => '商品B','user_id' => $user2->id]);

        // 商品Bにのみいいねする
        $user1->favoriteItems()->attach($item2->id);

        // 検索
        $response = $this->get('/search?keyword=A');

        // 検索後
        $response->assertSee('商品A');
        $response->assertDontSee('商品B');

        // マイリストタブ表示
        $response = $this->get('/search?keyword=A&tab=mylist');
        // 後述のextractMylistHtmlメソッド使用
        $mylistHtml = $this->extractMylistHtml($response);

        // 何も表示されない
        $this->assertStringNotContainsString('商品A', $mylistHtml);
        $this->assertStringNotContainsString('商品B', $mylistHtml);
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
