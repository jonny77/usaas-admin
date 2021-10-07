<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('tenant_id');
            $table->string('name', 100)->default('')->comment('租户名称');
            $table->string('domain', 100)->default('')->comment('域名前缀');
            $table->string('username', 32)->unique('username')->default('')->comment('用户名');
            $table->string('mobile', 32)->default('')->comment('手机号');
            $table->tinyInteger('status')->default(0)->comment('状态 0 禁用 1 启用');
            $table->string('email', 100)->default('')->comment('邮箱');
            $table->timestamp('start_time')->nullable()->comment('开始时间');
            $table->timestamp('end_time')->nullable()->comment('结束时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
