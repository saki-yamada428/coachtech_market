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

class ShowTest extends TestCase
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

    // Itemテーブルのレコード内容が表示される
    public function test_item_detail_displays_item_information()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id'      => $user->id,
            'name'         => 'テスト商品',
            'picture'      => 'png/box_danbo-ru_close.png',
            'brand'        => 'Brand',
            'price'        => 1000,
            'description'  => 'これは説明文です',
            'condition_id' => 1, // ConditionSeeder で ID=1 が存在する前提
        ]);

        $response = $this->get('/items/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('テスト商品');
        $response->assertSee('これは説明文です');
        $response->assertSee('Brand');
        $response->assertSee('1,000');
        $response->assertSee($item->condition->name); // Seeder の condition 名が表示される
        // 画像の表示確認（img タグの src にパスが含まれているか）
        $response->assertSee('png/box_danbo-ru_close.png');

    }

    // 複数選択されたカテゴリーがすべて表示される
    public function test_item_detail_displays_all_selected_categories()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'カテゴリー商品',
            'user_id' => $user->id,
        ]);

        // $cat1 = Category::factory()->create(['name' => '家電']);
        // $cat2 = Category::factory()->create(['name' => 'インテリア']);

        // カテゴリーの紐付け
        $item->categories()->attach([2, 3]);

        $response = $this->get('/items/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('家電');
        $response->assertSee('インテリア');
    }

    // いいね数の表示
    public function test_item_detail_displays_favorite_count()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'name' => 'お気に入り商品',
            'user_id' => $user->id,
        ]);

        // favorite を3件作成（Factoryなしで pivot に直接 insert）
        DB::table('favorites')->insert([
            ['user_id' => User::factory()->create()->id, 'item_id' => $item->id],
            ['user_id' => User::factory()->create()->id, 'item_id' => $item->id],
            ['user_id' => User::factory()->create()->id, 'item_id' => $item->id],
        ]);

        $response = $this->get('/items/' . $item->id);

        $response->assertStatus(200);

        // favorite 数が 3 と表示されていることを確認
        $response->assertSee('<span class="fav-count">3</span>', false);
    }

    // コメント数とコメント内容の表示
    public function test_item_detail_displays_comments_information()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // コメントユーザーのプロフィール作成
        $profile1 =Profile::factory()->create([
            'user_id' => $user1->id,
            'nickname' => 'ユーザー1',
            'picture' => 'png/ダミー用プロフィール画像.png',
        ]);
        $profile2 =Profile::factory()->create([
            'user_id' => $user2->id,
            'nickname' => 'ユーザー2',
            'picture' => 'png/ダミー用プロフィール画像.png',
        ]);

        //商品作成
        $item = Item::factory()->create([
            'name' => 'コメント用商品',
            'user_id' => User::factory()->create()->id,
        ]);

        // コメント作成
        $comment1 = Comment::factory()->create([
            'user_id' => $user1->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント1',
        ]);
        $comment2 = Comment::factory()->create([
            'user_id' => $user2->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント2',
        ]);

        $response = $this->get('/items/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('<span class="comment-count">2</span>', false);
        $response->assertSee('ユーザー1');
        $response->assertSee('ユーザー2');
        $response->assertSee('テストコメント1');
        $response->assertSee('テストコメント2');
        // png/ダミー用プロフィール画像.pngの表示回数が2回
        $this->assertEquals(2, substr_count(
            $response->getContent(), 'png/ダミー用プロフィール画像.png'));
    }
}
