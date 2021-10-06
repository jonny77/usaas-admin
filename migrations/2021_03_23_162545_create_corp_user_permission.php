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
            $table->integer('corp_id')->default(0)->comment('企业ID');
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
