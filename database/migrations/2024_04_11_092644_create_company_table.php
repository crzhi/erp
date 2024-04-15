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
        Schema::create('company', function (Blueprint $table) {
            $table->comment('企业管理');
            $table->increments('id');
            $table->string('short_name')->nullable()->comment('企业简称');
            $table->string('name')->nullable()->comment('企业名称');
            $table->string('desc')->default('')->comment('企业描述');
            $table->integer('class')->index()->comment('企业分类');
            $table->integer('sort')->index()->comment('排序');
            $table->integer('status')->index()->comment('状态');
            $table->integer('supplier')->index()->comment('是供应商');
            $table->integer('client')->index()->comment('是客户');
            $table->string('logo')->nullable()->comment('logo');
            $table->string('code')->index()->nullable()->comment('信用代码');
            $table->string('id_img')->nullable()->comment('营业执照');
            $table->string('city')->nullable()->comment('联系城市');
            $table->string('contacts')->nullable()->comment('联系人');
            $table->string('mobile')->nullable()->comment('联系电话');
            $table->string('address')->nullable()->comment('详细地址');
            $table->string('email')->nullable()->comment('联系邮箱');
            $table->string('bank_type')->nullable()->comment('开户平台');
            $table->string('bank_account')->nullable()->comment('银行户名');
            $table->string('bank_no')->nullable()->comment('银行户号');
            $table->string('bank_address')->nullable()->comment('开户地址');
            $table->integer('Invoicing')->index()->nullable()->comment('开发票');
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
        Schema::dropIfExists('company');
    }
};
