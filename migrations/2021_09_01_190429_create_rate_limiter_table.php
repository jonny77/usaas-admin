<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateRateLimiterTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_rate_limiter', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('count')->default(0)->comment('QPS');
            $table->integer('capacity')->default(0)->comment('峰值QPS');
            $table->integer('ttl')->default(0)->comment('ttl');
            $table->string('request_uri', 255)->default("")->comment('请求路由');
            $table->string('request_method', 50)->default("")->comment('请求方法');
            $table->string('limit_start_time', 50)->default("")->comment('限制开启');
            $table->string('limit_end_time', 50)->default("")->comment('限制结束');
            $table->integer('order')->default(0)->comment('排序');
            $table->integer('status')->default(0)->comment('0无效 1 有效');
            $table->string('remark', 500)->default("")->comment('扩展参数');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_rate_limiter');
    }
}
