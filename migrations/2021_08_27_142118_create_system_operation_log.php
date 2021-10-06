<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateSystemOperationLog extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_operation_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('path', 255)->default('')->comment('路径');
            $table->integer('user_id')->default(0)->comment('操作员id');
            $table->string('method', 10)->default('')->comment('请求方法');
            $table->string('ip', 255)->default('')->comment('IP');
            $table->json('request')->comment('请求数据');
            $table->json('result')->comment('响应数据');
            $table->json('header')->comment('请求头信息');
            $table->string('runtime', 255)->default('')->comment('运行时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_operation_log');
    }
}
