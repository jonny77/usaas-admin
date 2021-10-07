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
class RolesValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'role_id' => 'integer',
        'role_ids' => 'array',
        'scopes' => 'array',
        'role_name' => 'string|length:0,20',
        'slug' => 'string|length:0,50',
        'parent_id' => 'integer',
        'user_id' => 'integer',
        'permissions' => 'array',
        'remark' => 'string|length:0,255',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'role_id' => 'ID',
        'role_ids' => '权限组ID',
        'scopes' => '权限范围',
        'role_name' => '角色名',
        'permissions' => '权限ID',
        'slug' => '角色的标识',
        'parent_id' => '父级ID',
        'user_id' => '员工ID',
        'create_user_id' => '创建人ID',
        'remark' => '角色备注',
    ];

    protected $scene = [
        'create' => ['role_name', 'slug', 'remark', 'permissions'],

        'update' => ['role_name', 'slug', 'remark', 'permissions'],
        'delete' => 'role_id',
        'list' => ['limit', 'page'],
    ];
}
