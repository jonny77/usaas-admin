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

class CreateSystemRoleUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_role_users', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->integer('user_id')->default(0)->comment('员工ID');
            $table->integer('corp_id')->default(0)->comment('企业ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_role_users');
    }
}