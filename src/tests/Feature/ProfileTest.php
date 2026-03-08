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

class ProfileTest extends TestCase
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

    // 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_profile_edit_displays()
    {
        // 必要な情報を作成
        $user = User::factory()->create();
        $this->actingAs($user);

        $profile =Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
            'postal_code' => '999-9999',
            'address' => '東京都品川区',
            'building' => 'マンション101',
        ]);

        // プロフィール変更画面表示
        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        // 表示確認
        $response->assertSee('png/ダミー用プロフィール画像.png');
        $response->assertSee('ユーザー1');
        $response->assertSee('999-9999');
        $response->assertSee('東京都品川区');
        $response->assertSee('マンション101');
    }
}
