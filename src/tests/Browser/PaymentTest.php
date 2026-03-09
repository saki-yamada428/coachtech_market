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

class PaymentTest extends TestCase
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

    // 支払い方法がリアルタイムで反映される
    public function test_payment_method_display()
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

        // 初期状態で未選択
        $response->assertSee('未選択');
    }

    public function test_payment_method_realtime_update()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/purchase/1')
                ->select('#payment_method', 'card')
                ->assertSeeIn('#paymentSummary', 'card');
        });
    }
}
