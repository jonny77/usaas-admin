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

class CreateSystemUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username', 32)->unique('username')->default('')->comment('用户名');
            $table->string('mobile', 32)->default('')->comment('手机号');
            $table->string('nickname', 32)->default('')->comment('昵称');
            $table->string('email', 100)->default('')->comment('邮箱');
            $table->string('password', 64)->default('')->comment('密码');
            $table->string('avatar', 1000)->default('')->comment('头像');
            $table->string('description', 1000)->default('')->comment('描述');
            $table->tinyInteger('sex')->default(0)->comment('性别：1男;2女 0未知');
            $table->tinyInteger('status')->default(0)->comment('状态 0 禁用 1 启用');
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->integer('department_id')->default(0)->comment('部门ID');
            $table->integer('create_user_id')->default(0)->comment('创建人ID');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->unique(['username', 'tenant_id']);
            $table->index(['username']);
            $table->index(['email']);
            $table->timestamps();
            $table->comment('管理员表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_users');
    }
}
