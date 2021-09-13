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
namespace UU\Admin\Model;

/**
 * @property int $module_id
 * @property string $name 模型标识
 * @property string $title 模型名称
 * @property string $primary_key 主键字段
 * @property int $order 排序
 * @property int $status
 * @property int $is_virtual
 * @property string $attributes 字段列表
 * @property string $list_component 列表组件
 * @property string $add_component 添加组件
 * @property string $edit_component 编辑组件
 * @property string $grid 列表定义
 * @property string $filter 过滤器
 * @property array $form form
 * @property string $order_field 排序
 * @property string $engine_type 数据库引擎
 * @property string $route 路由
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemModule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_modules';

    protected $primaryKey = 'module_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_id', 'name', 'title', 'primary_key', 'order', 'status', 'form', 'is_virtual', 'attributes', 'list_component', 'add_component', 'edit_component', 'grid', 'filter', 'order_field', 'engine_type', 'route', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['module_id' => 'integer',
        'attributes' => 'array',
        'form' => 'array',
        'grid' => 'array',
        'filter' => 'array',
        'order_field' => 'array',
        'order' => 'integer', 'is_virtual' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', ];
}
