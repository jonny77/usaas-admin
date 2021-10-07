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

class CreateSystemRolePermissions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_role_permissions', function (Blueprint $table) {
            $table->unsignedInteger('role_permission_id', true);
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->integer('permission_id')->default(0)->comment('权限ID');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_role_permissions');
    }
}
