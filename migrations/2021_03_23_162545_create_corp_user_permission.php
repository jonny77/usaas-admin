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

class CreateCorpUserPermission extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_user_permissions', function (Blueprint $table) {
            $table->unsignedInteger('user_permission_id', true);
            $table->integer('user_id')->default(0)->comment('员工ID');
            $table->integer('permission_id')->default(0)->comment('权限ID');
            $table->integer('create_user_id')->default(0)->comment('权限创建人ID');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user_permissions');
    }
}
