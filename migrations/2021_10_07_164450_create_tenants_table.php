<?php

declare(strict_types=1);
/**
 * This file is part of usaas.
 *
 * @link     https://www.uupt.com
 * @document https://www.uupt.com
 * @contact maozihao@uupaotui.com
 * @license  https://github.com/uu-paotui/usaas/blob/main/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

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
            $table->integer('data_source_id')->default(0)->comment('数据源ID 关联system_data_source表 默认0:数据库字段隔离');
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
