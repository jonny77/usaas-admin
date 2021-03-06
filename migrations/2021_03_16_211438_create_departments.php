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

class CreateDepartments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_departments', function (Blueprint $table) {
            $table->bigIncrements('department_id')->comment('部门ID');
            $table->string('name', 100)->default(null)->comment('应用名称');
            $table->string('full_path', 1000)->default('')->comment('权限节点');
            $table->string('remark', 1000)->default('')->comment('备注');
            $table->integer('order')->default(0)->comment('排序');
            $table->integer('parent_id')->default(0)->comment('上级ID');
            $table->tinyInteger('status')->default(0)->comment('状态 0 禁用 1 启用');
            $table->integer('create_user_id')->default(0)->comment('创建人ID');
            $table->integer('employee_num')->default(0)->comment('部门人数');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_departments');
    }
}
