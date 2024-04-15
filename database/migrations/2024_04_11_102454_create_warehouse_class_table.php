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
        Schema::create('warehouse_class', function (Blueprint $table) {
            $table->comment('仓库管理');
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->integer('parent_id')->nullable()->index()->comment('上层分类');
            $table->integer('sort')->index()->comment('排序');
            $table->integer('status')->index()->default(new \Illuminate\Database\Query\Expression('1'))->comment('状态');
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
        Schema::dropIfExists('warehouse_class');
    }
};
