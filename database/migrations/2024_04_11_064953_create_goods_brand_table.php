<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_brand', function (Blueprint $table) {
            $table->comment('商品品牌');
            $table->increments('id');
            $table->string('name')->default('')->comment('品牌名称');
            $table->string('website')->nullable()->default('')->comment('官网地址');
            $table->integer('class')->index()->comment('品牌分类');
            $table->string('desc')->default('')->comment('品牌描述');
            $table->integer('sort')->index()->comment('排序');
            $table->string('logo')->default('')->comment('品牌Logo');
            $table->integer('status')->index()->comment('品牌状态');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_brand');
    }
};
