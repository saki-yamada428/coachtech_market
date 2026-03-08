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
// CSRF無効化
use App\Http\Middleware\VerifyCsrfToken;
// 画像アップロードのテスト
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ItemController;

class SellTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    // use DatabaseMigrations;

    // 毎回コンディションシーダーとカテゴリーシーダーを実行
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\ConditionSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);
    }

    // 商品出品ができる
    public function test_sell_item()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // 必要な情報を作成
        $user = User::factory()->create();
        $this->actingAs($user);

        // プロフィールがないと弾く設定のため作成
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'nickname' => 'ユーザー1',
        ]);

        // 出品ページ画面表示
        $response = $this->get('/sell');
        $response->assertStatus(200);

        // 商品画像保存用のフェイクストレージ作成
        Storage::fake('public');

        $filePath = base_path('tests/Fixtures/box_danbo-ru_close.png');

        $uploadedFile = new UploadedFile(
            $filePath,                // 実ファイルのパス
            'box_danbo-ru_close.png', // アップロード時のファイル名
            'image/png',              // MIMEタイプ
            null,
            true                      // テスト用に「本物のアップロード」として扱う
        );

        // フォーム内容作成
        $request = ExhibitionRequest::create('/sell', 'POST', [
            'name'         => '出品商品',
            'brand'        => 'Brand',
            'price'        => 1000,
            'description'  => 'これは説明文です',
            'condition_id' => 1,
            'category_id'  => [2,3],
            // 'picture' => $uploadedFile,
        ]);

        // ファイルを手動でセット（これが絶対必要）
        $request->files->set('picture', $uploadedFile);

        // 認証ユーザーをセット
        $request->setUserResolver(fn() => $user);

        // バリデーションを手動で通す
        $validator = Validator::make(
            $request->all(),
            (new ExhibitionRequest)->rules()
        );
        $request->setValidator($validator);

        // コントローラ呼び出し
        $controller = new ItemController();
        $response = $controller->store($request);

        $item = Item::first();

        // Item が作成されている
        $this->assertDatabaseHas('items', [
            'user_id'      => $user->id,
            'name'         => '出品商品',
            // 'picture'      => 'png/box_danbo-ru_close.png',
            'brand'        => 'Brand',
            'price'        => 1000,
            'description'  => 'これは説明文です',
            'condition_id' => 1,
        ]);

        // picture の検証
        $this->assertStringStartsWith('png/', $item->picture);
        Storage::disk('public')->assertExists($item->picture);

        // カテゴリーの紐づけができている
        $this->assertDatabaseHas('category_item', [
            'item_id'     => $item->id,
            'category_id' => 2,
        ]);

        $this->assertDatabaseHas('category_item', [
            'item_id'     => $item->id,
            'category_id' => 3,
        ]);
    }
}
