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

class AddressTest extends TestCase
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

    // 送付先住所変更画面の変更が購入画面で反映される、かつOrderテーブルに保存
    public function test_send_address_change_display()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id'      => User::factory()->create()->id,
            'name'         => '購入テスト商品',
            'picture'      => 'png/box_danbo-ru_close.png',
            'price'        => 8888,
        ]);

        $profile =Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー',
            'postal_code' => '999-9999',
            'address' => '東京都品川区',
            'building' => 'マンション101',
        ]);

        // 購入画面表示
        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        // プロフィールの住所が表示されている(初期状態)
        $response->assertSee('999-9999');
        $response->assertSee('東京都品川区');
        $response->assertSee('マンション101');

        // 住所変更画面表示
        $response = $this->get('/purchase/address/' . $item->id);

        // プロフィールの住所が表示されている(初期状態)
        $response->assertSee('999-9999');
        $response->assertSee('東京都品川区');
        $response->assertSee('マンション101');

        // 変更をセッションに保存
        session([
            'postal_code' => '777-7777',
            'address' => '北海道札幌市',
            'building' => 'アパート202',
        ]);

        // 購入画面に戻る
        $response = $this->get('/purchase/' . $item->id);
        $response->assertStatus(200);

        // 変更内容が表示されている
        $response->assertSee('777-7777');
        $response->assertSee('北海道札幌市');
        $response->assertSee('アパート202');


    // 送付先住所変更画面の変更内容がOrderテーブルに登録される

        // セッションに支払い方法追加
        session([
            'payment_method' => 'コンビニ払い',
        ]);

        // Stripeで決済完了した前提
        $response = $this->get('/purchase/' . $item->id . '/success');

        // リダイレクト確認
        $response->assertRedirect(route('items.index'));

        // Order が作成されている
        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'postal_code' => '777-7777',
            'address' => '北海道札幌市',
            'building' => 'アパート202',
            'payment_method' => 'コンビニ払い',
        ]);
    }
}
