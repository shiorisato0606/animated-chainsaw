<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Company;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $cocacola = Company::where('company_name', 'コカ・コーラ')->first();
        $suntory = Company::where('company_name', 'サントリー')->first();
        $kirin = Company::where('company_name', 'キリン')->first();

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

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
