<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
        ];

        $names = [
            '腕時計',
            'HDD',
            '玉ねぎ3束',
            '革靴',
            'ノートPC',
            'マイク',
            'ショルダーバッグ',
            'タンブラー',
            'コーヒーミル',
            'メイクセット',
        ];

        $pictures = [
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
        ];

        $brands = [
            'Rolax',
            '西芝',
            'なし',
            '',
            '',
            'なし',
            '',
            'なし',
            'Starbacks',
            '',
        ];

        $prices = [
            '15000',
            '5000',
            '300',
            '4000',
            '45000',
            '8000',
            '3500',
            '500',
            '4000',
            '2500',
        ];

        $descriptions = [
            'スタイリッシュなデザインのメンズ腕時計',
            '高速で信頼性の高いハードディスク',
            '新鮮な玉ねぎ3束のセット',
            'クラシックなデザインの革靴',
            '高性能なノートパソコン',
            '高音質のレコーディング用マイク',
            'おしゃれなショルダーバッグ',
            '使いやすいタンブラー',
            '手動のコーヒーミル',
            '便利なメイクアップセット',
        ];

        $conditions = [
            1,
            2,
            3,
            4,
            1,
            2,
            3,
            4,
            1,
            2,
        ];

        // 10件分をまとめて作成
        for ($i = 0; $i < count($names); $i++) {
            Item::create([
                'user_id'     => $users[$i],
                'name'        => $names[$i],
                'picture'     => $pictures[$i],
                'brand'       => $brands[$i],
                'price'       => $prices[$i],
                'description' => $descriptions[$i],
                'condition_id'   => $conditions[$i],
            ]);
        }


        // ファクトリーから作成
        \App\Models\Item::factory(3)->create();
    }
}

