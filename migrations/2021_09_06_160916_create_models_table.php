<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_modules', function (Blueprint $table) {
            $table->bigIncrements('module_id');
            $table->string('name', 255)->default("")->comment('模型标识');
            $table->string('title', 255)->default("")->comment('模型名称');
//            $table->integer('extend')->default(0)->comment('继承的模型');
            $table->string('primary_key', 50)->default("")->comment('主键字段');
            $table->tinyInteger('is_virtual')->default(1)->comment('是否虚拟表格');
            $table->integer('order')->default(0)->comment('排序');
            $table->json('attributes')->comment('字段列表');
            $table->string('list_component', 255)->default("")->comment('列表组件');
            $table->string('add_component', 255)->default("")->comment('添加组件');
            $table->string('edit_component', 255)->default("")->comment('编辑组件');
            $table->json('grid')->comment('列表定义');
            $table->json('filter')->comment('过滤器');
            $table->json('order_field')->comment('排序');
            $table->string('engine_type', 25)->default("MyISAM")->comment('数据库引擎');
            $table->string('route', 255)->default("")->comment('路由');
            $table->tinyInteger('status')->default(0)->comment('状态 0 禁用 1 启用');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
            $table->comment("模型表");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
}
