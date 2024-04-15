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
        Schema::create('warehouse', function (Blueprint $table) {
            $table->comment('仓库管理');
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->string('alias')->nullable()->comment('别名');
            $table->string('desc')->nullable()->comment('仓库描述');
            $table->integer('sort')->index()->default(new \Illuminate\Database\Query\Expression('0'))->comment('排序');
            $table->integer('status')->index()->default(new \Illuminate\Database\Query\Expression('0'))->comment('状态');
            $table->string('city')->nullable()->comment('所在区域');
            $table->string('address')->nullable()->comment('详细地址');
            $table->string('contacts')->nullable()->comment('联系人');
            $table->string('mobile')->nullable()->comment('联系电话');
            $table->string('email')->nullable()->comment('邮箱');
            $table->integer('class')->nullable()->comment('仓库分类');
            $table->text('position')->comment('仓位');
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
        Schema::dropIfExists('warehouse');
    }
};
