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
        Schema::create('goods', function (Blueprint $table) {
            $table->comment('商品管理');
            $table->increments('id');
            $table->string('title')->default('')->comment('标题');
            $table->string('coding')->default('')->comment('编码');
            $table->integer('brand')->index()->comment('品牌');
            $table->integer('class')->index()->comment('商品分类');
            $table->string('desc')->default('')->comment('商品描述');
            $table->integer('status')->index()->comment('商品状态');
            $table->integer('pass_status')->index()->comment('审批状态');
            $table->text('rich_text')->comment('富文本内容');
            $table->text('sku')->comment('规格相关信息');
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
        Schema::dropIfExists('goods');
    }
};
