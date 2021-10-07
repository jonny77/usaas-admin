<?php

declare(strict_types=1);

/**
 * This file is UUOA of Hyperf.plus
 *
 * @link     https://www.hyperf.plus
 * @document https://doc.hyperf.plus
 * @contact  4213509@qq.com
 * @license  https://github.com/hyperf-plus/admin/blob/master/LICENSE
 */

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateSystemPermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_permissions', function (Blueprint $table) {
            $table->unsignedInteger('permission_id', true);
            $table->string('name', 20)->default('')->comment('权限名');
            $table->string('slug', 50)->default('')->comment('权限标识');
            $table->string('url', 255)->default('')->comment('url地址');
            $table->string('icon', 255)->default('')->comment('图标');
            $table->string('component', 255)->default('')->comment('显示组件');
            $table->tinyInteger('is_external_link')->default(0)->comment('是否外链');
            $table->tinyInteger('is_display')->default(0)->comment('是否显示');
            $table->tinyInteger('type')->default(0)->comment('类型：0目录;1菜单;按钮');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->tinyInteger('keepalive')->default(0)->comment('是否缓存 0禁用 1 启用');
            $table->integer('order')->default(0)->comment('排序');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->integer('create_user_id')->default(0)->comment('创建人ID');
            $table->string('remark', 255)->default('')->comment('权限备注');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_permissions');
    }
}
