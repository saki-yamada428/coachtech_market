<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();

        // user_id,item_idの組み合わせが被らないように
        // itemをシャッフルして上から10件だけ使う
        $items = \App\Models\Item::inRandomOrder()->take(10)->get();

        // 取得した10件の注文を作る
        foreach ($items as $item) {
            Order::factory()->create([
                'user_id' => $users->random()->id,
                'item_id' => $item->id,
            ]);
        }
    }
}
