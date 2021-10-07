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
class SystemConfigValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'name' => 'string|length:0,255',
        'field' => 'string|length:0,255',
        'key' => 'string|length:0,255',
        'parent_id' => 'integer',
        'order' => 'integer',
        'required' => 'integer|in:0,1',
        'value' => 'string|length:0,1000',
        'component' => 'string|length:0,255',
        'status' => 'integer',
        'type' => 'integer',
        'field_type' => 'string|length:0,20',
        'options' => 'string|length:0,2000',
        'icon' => 'string|length:0,255',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'name' => '配置名称',
        'field' => '配置名称',
        'options' => '扩展参数',
        'key' => '配置键名',
        'parent_id' => '父级',
        'field_type' => '字段类型',
        'order' => '排序',
        'value' => '值',
        'component' => '组件',
        'status' => '0无效 1 有效',
        'created_at' => '',
        'updated_at' => '',
        'type' => '值类型',
        'icon' => '',
    ];

    protected $scene = [
        'create' => [
            'name',
            'field',
            'field_type',
            'key',
            'parent_id',
            'order',
            'value',
            'options',
            'component',
            'status',
            'required',
            'type',
            'icon',
        ],
        'update' => [
            'name',
            'field',
            'key',
            'parent_id',
            'order',
            'field_type',
            'value',
            'options',
            'required',
            'component',
            'status',
            'type',
            'icon',
        ],
        'delete' => 'id',
        'list' => ['limit', 'page'],
    ];
}
