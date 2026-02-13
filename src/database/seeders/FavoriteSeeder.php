<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 各ユーザーに1～5個のお気に入りを登録
        // UserテーブルとItemテーブルを全件取得
        $users = \App\Models\User::all();
        $items = \App\Models\Item::all();

        // 全てのUserに対し、お気に入りを作る
        foreach ($users as $user) {
            $user->favorites()->attach(
                $items->random(rand(1, 5)) //全商品から1～5個ランダムに選ぶ
                ->pluck('id') //選んだ商品からIdだけを取り出す
                ->mapWithKeys(function ($id) { //商品IDとTimestampのセットという形に変換
                    return [
                        $id => [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                })->toArray() //Laravel の attach() が受け取れる形に変換
            );
        }
    }
}
