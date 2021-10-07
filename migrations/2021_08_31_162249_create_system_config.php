<?php

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateSystemConfig extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_config', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name', 255)->default('')->comment('配置名称');
            $table->string('field', 255)->default('')->comment('字段名');
            $table->string('key', 255)->default('')->comment('配置键名');
            $table->integer('parent_id')->default(0)->comment('父级');
            $table->integer('order')->default(0)->comment('排序');
            $table->string('value', 1000)->default("")->comment('值');
            $table->tinyInteger('type')->default(0)->comment('值类型');
            $table->tinyInteger('required')->default(0)->comment('0可选 1必填');
            $table->string('component', 255)->default("Input")->comment('组件');
            $table->string('icon', 255)->default("")->comment('图标');
            $table->string('field_type', 255)->default("")->comment('字段类型');
            $table->string('options', 1000)->default("")->comment('扩展参数');
            $table->integer('status')->default(0)->comment('0无效 1 有效');
            $table->integer('tenant_id')->default(0)->comment('租户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_config');
    }
}
