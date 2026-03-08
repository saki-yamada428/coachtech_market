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

class PurchaseTest extends TestCase
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

    // 購入するボタンを押下で購入 → 商品一覧でSoldと表示されている
    public function test_item_purchase_success_sold_display()
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

        // プロフィールの住所が表示されている
        $response->assertSee('999-9999');
        $response->assertSee('東京都品川区');
        $response->assertSee('マンション101');

        // Stripe の代わりに session に値を入れる
        session([
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
            'payment_method' => 'カード払い',
        ]);

        // Stripeで決済完了した前提
        $response = $this->get('/purchase/' . $item->id . '/success');

        // リダイレクト確認
        $response->assertRedirect(route('items.index'));

        // Order が作成されている
        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
            'payment_method' => 'カード払い',
        ]);

        // 商品がSoldになっている（items.index の表示で確認）
        $response = $this->get(route('items.index'));
        $response->assertSee('Sold');
    }

    //マイページ画面で購入した商品に表示されている
    public function test_sold_item_mypage_display()
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
            'picture' => 'png/ダミー用プロフィール画像.png',
            'postal_code' => '999-9999',
            'address' => '東京都品川区',
            'building' => 'マンション101',
        ]);

        // Stripe の代わりに session に値を入れる
        session([
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
            'payment_method' => 'カード払い',
        ]);

        // Stripeで決済完了した前提
        $response = $this->get('/purchase/' . $item->id . '/success');

        // リダイレクト確認
        $response->assertRedirect(route('items.index'));

        // Order が作成されている
        $this->assertDatabaseHas('orders', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
            'payment_method' => 'カード払い',
        ]);

        // マイページの購入した商品に表示されている
        $response = $this->get('/mypage');
        $response->assertSee('購入テスト商品');
        $response->assertSee('png/box_danbo-ru_close.png');
    }
}
