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
        Schema::create('company_class', function (Blueprint $table) {
            $table->comment('企业分类');
            $table->increments('id');
            $table->string('name')->default('')->comment('名称');
            $table->integer('status')->index()->comment('状态');
            $table->string('sort')->default('')->comment('排序');
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
        Schema::dropIfExists('company_class');
    }
};
