<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Product;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        // コカ・コーラのデータを挿入
        $cocacola = Company::create([
            'company_name' => 'コカ・コーラ',
        ]);

        // サントリーのデータを挿入
        $suntory = Company::create([
            'company_name' => 'サントリー',
        ]);

        // キリンのデータを挿入
        $kirin = Company::create([
            'company_name' => 'キリン',
        ]);

        // 商品のダミーデータを挿入
        $products = [
            [
                'product_name' => 'コーラ',
                'price' => 1000,
                'stock' => 100,
                'company_id' => $cocacola->id,
            ],
            [
                'product_name' => 'ソーダ',
                'price' => 1000,
                'stock' => 100,
                'company_id' => $suntory->id,
            ],
            [
                'product_name' => '水',
                'price' => 1000,
                'stock' => 100,
                'company_id' => $kirin->id,
            ],
        ];

        // 商品を作成
        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
