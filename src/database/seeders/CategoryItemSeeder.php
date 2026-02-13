<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Itemとカテゴリーの紐づけ
        // Itemsテーブルとcategoriesテーブルを全件取得
        $items = \App\Models\Item::all();
        $categories = \App\Models\Category::all();

        // 全てのItemに対し1～3のカテゴリーを付加
        foreach ($items as $item) {
            $item->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
