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
        Schema::create('purchase', function (Blueprint $table) {
            $table->comment('采购订单');
            $table->increments('id');
            $table->string('coding')->unique()->default('')->comment('采购编码');
            $table->string('order_no')->unique()->default('')->comment('订单号');
            $table->integer('company')->index()->comment('供应商');
            $table->integer('pay_type')->index()->comment('付款方式');
            $table->integer('status')->nullable()->default(0)->index()->comment('采购单状态');
            $table->string('start_in_time')->nullable()->index()->comment('开始入库时间');
            $table->string('start_purchase')->nullable()->index()->comment('开始采购时间');
            $table->string('pass_time')->nullable()->index()->comment('审核时间');
            $table->string('over_time')->nullable()->index()->comment('完成时间');
            $table->string('admin_remarks')->nullable()->default('')->comment('管理备注');
            $table->string('order_remarks')->nullable()->default('')->comment('单据备注');
            $table->string('contacts')->nullable()->default('')->comment('联系人');
            $table->string('mobile')->nullable()->default('')->comment('联系电话');
            $table->text('detail')->default('')->comment('采购详情');
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
        Schema::dropIfExists('purchase');
    }
};
