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
        Schema::create('goods_class', function (Blueprint $table) {
            $table->comment('商品分类');
            $table->increments('id');
            $table->integer('parent_id')->nullable()->index()->comment('上层ID');
            $table->string('name')->default('')->comment('分类名称');
            $table->integer('sort')->index()->comment('排序');
            $table->integer('status')->index()->comment('状态');
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
        Schema::dropIfExists('goods_class');
    }
};
