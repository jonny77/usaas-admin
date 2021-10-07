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
namespace UU\Admin\Validate;

use HPlus\Validate\Validate;

/**
 * 验证器类.
 */
class SystemModulesValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'model_id' => '',
        'name' => 'string|length:0,255',
        'title' => 'string|length:0,255',
        'need_pk' => 'integer',
        'status' => 'integer',
        'order' => 'integer',
        'attributes' => 'array',
        'form' => 'array',
        'list_component' => 'string|length:0,255',
        'add_component' => 'string|length:0,255',
        'edit_component' => 'string|length:0,255',
        'grid' => 'array',
        'filter' => 'array',
        'order_field' => 'array',
        'engine_type' => 'string|length:0,25',
        'route' => 'string|length:0,255',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'model_id' => '',
        'name' => '模型标识',
        'status' => '状态',
        'title' => '模型名称',
        'need_pk' => '新建表时是否需要主键字段',
        'order' => '排序',
        'attributes' => '字段列表',
        'list_component' => '列表组件',
        'add_component' => '添加组件',
        'edit_component' => '编辑组件',
        'grid' => '列表定义',
        'filter' => '过滤器',
        'order_field' => '排序',
        'engine_type' => '数据库引擎',
        'route' => '路由',
    ];

    protected $scene = [
        'create' => [
            'name',
            'title',
            'need_pk',
            'status',
            'order',
            'attributes' => 'array|require',
            'list_component',
            'add_component',
            'edit_component',
            'grid' => 'array|require',
            'filter' => 'array|require',
            'order_field' => 'array|require',
            'form' => 'array|require',
            'engine_type',
            'route',
        ],
        'update' => [
            'name',
            'title',
            'need_pk',
            'order',
            'attributes',
            'status',
            'list_component',
            'add_component',
            'edit_component',
            'grid',
            'filter',
            'order_field',
            'engine_type',
            'route',
        ],
        'delete' => 'id',
        'list' => ['limit', 'page'],
    ];
}
