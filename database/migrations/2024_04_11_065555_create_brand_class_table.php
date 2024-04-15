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
        Schema::create('brand_class', function (Blueprint $table) {
            $table->comment('品牌分类');
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->integer('status')->index()->default(new \Illuminate\Database\Query\Expression('1'))->comment('状态');
            $table->integer('sort')->index()->comment('排序');
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
        Schema::dropIfExists('brand_class');
    }
};
