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
        Schema::create('put_warehouse', function (Blueprint $table) {
            $table->comment('入库表');
            $table->increments('id');
            $table->string('purchase_coding')->index()->nullable()->comment('采购单号');
            $table->string('company')->index()->default('')->comment('供应商');
            $table->string('goods')->index()->default('')->comment('产品');
            $table->string('sku')->default('')->comment('规格');
            $table->integer('number')->index()->comment('数量');
            $table->string('remarks')->default('')->comment('备注');
            $table->integer('warehouse')->index()->comment('仓库');
            $table->string('warehouse_position')->comment('库位');
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
        Schema::dropIfExists('put_warehouse');
    }
};
