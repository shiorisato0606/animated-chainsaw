<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('company_id'); 
            $table->string('product_name');
            $table->integer('price');
            $table->integer('stock');
            $table->text('comment')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
