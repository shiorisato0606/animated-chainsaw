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
