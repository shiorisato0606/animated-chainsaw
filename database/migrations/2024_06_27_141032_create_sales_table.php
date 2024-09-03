<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // id() メソッドは unsigned big integer の主キーを定義します
            $table->unsignedBigInteger('product_id'); // unsignedBigInteger を使用して外部キーを参照するカラムを定義します
            $table->integer('quantity'); // 購入数を格納するカラムを追加
            $table->decimal('total_price', 10, 2); // 合計金額を格納するカラムを追加
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
