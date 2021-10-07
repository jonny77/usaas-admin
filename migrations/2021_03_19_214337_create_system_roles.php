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

class CreateSystemRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_role', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('role_name', 20)->default('')->comment('角色名');
            $table->string('slug', 50)->default('')->comment('角色的标识');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->tinyInteger('status')->default(0)->comment('状态 1有效 0无效');
            $table->integer('create_user_id')->default(0)->comment('创建人ID');
            // $table->tinyInteger('data_range')->default(0)->comment('1 全部数据 2 自定义数据 3 仅本人数据 4 部门数据 5 部门及以下数据');
            $table->string('remark', 255)->default('')->comment('角色备注');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corp_roles');
    }
}
