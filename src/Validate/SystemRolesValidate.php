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
class SystemRolesValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'role_id' => 'integer',
        'status' => 'integer|in:0,1',
        'scopes' => 'array',
        'role_name' => 'string|length:0,20',
        'slug' => 'string|length:0,50',
        'parent_id' => 'integer',
        'super_administrator' => 'in:0,1',
        'sub_administrator' => 'in:0,1',
        'child_administrator' => 'in:0,1',
        'employee_id' => 'integer',
        'permissions' => 'array',
        'create_user_id' => 'integer',
        'remark' => 'string|length:0,255',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'status' => '状态',
        'role_id' => 'ID',
        'role_ids' => '权限组ID',
        'super_administrator' => '超级管理员',
        'sub_administrator' => '可以管理其他管理员',
        'child_administrator' => '管理子管理员',
        'scopes' => '权限范围',
        'role_name' => '角色名',
        'permissions' => '权限ID',
        'slug' => '角色的标识',
        'parent_id' => '父级ID',
        'employee_id' => '员工ID',
        'create_user_id' => '创建人ID',
        'remark' => '角色备注',
    ];

    protected $scene = [
        'create' => ['role_name', 'slug', 'status', 'remark', 'permissions'],
        'update' => ['role_name', 'slug', 'status', 'remark', 'permissions'],
        'delete' => 'role_id',
        'list' => ['limit', 'page'],
    ];
}
