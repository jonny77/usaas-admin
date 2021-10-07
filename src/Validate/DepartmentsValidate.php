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
class DepartmentsValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'department_ids' => 'array',
        'name' => 'string|length:0,100',
        'full_path' => 'string|length:0,1000',
        'order' => 'integer',
        'status' => 'integer|in:0,1',
        'parent_id' => 'integer',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'department_ids' => '部门ID列表',
        'name' => '应用名称',
        'full_path' => '权限节点',
        'order' => '排序',
        'status' => '状态',
        'department_id' => '部门ID',
        'parent_id' => '上级ID',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ];

    protected $scene = [
        'create' => [
            'name',
            'order',
            'status',
            'parent_id',
        ],
        'update' => [
            'name',
            'order',
            'status',
            'parent_id',
        ],
        'delete' => 'department_id',
        'list' => ['limit', 'page'],
    ];
}
