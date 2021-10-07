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
class SystemUserValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'user_id' => '',
        'username' => 'string|require|length:6,16|unique:system_users,username',
        'password' => 'string|length:6,64',
        'password_confirm' => 'string|length:6,64',
        'old_password' => 'string|length:6,64',
        'avatar' => 'string|length:0,255',
        'description' => 'string|length:0,255',
        'email' => 'string|length:0,64',
        'mobile' => 'string|length:0,32',
        'nickname' => 'string|length:0,64',
        'sex' => 'integer',
        'roles' => 'array',
        'status' => 'integer',
        'department_id' => 'integer',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'user_id' => '用户ID',
        'roles' => '权限ID列表',
        'username' => '用户名',
        'password' => '密码',
        'avatar' => '头像',
        'description' => '描述',
        'email' => '邮箱',
        'mobile' => '手机号',
        'nickname' => '昵称',
        'sex' => '性别：1男;2女 0未知',
        'status' => '状态(0无效;1有效)',
        'role_id' => '角色ID',
        'department_id' => '部门ID',
        'created_at' => '创建时间',
        'create_user_id' => '创建人',
        'updated_at' => '修改时间',
    ];

    protected $scene = [
        'create' => [
            'username',
            'password',
            'avatar',
            'roles',
            'description',
            'email',
            'mobile',
            'nickname',
            'sex',
            'status',
            'department_id',
        ],
        'update' => [
            'avatar',
            'description',
            'password',
            'email',
            'roles',
            'mobile',
            'nickname',
            'sex',
            'status',
            'department_id',
        ],
        'password' => [
            'old_password',
            'password',
            'password_confirm',
        ],
        'delete' => 'user_id',
        'list' => ['limit', 'page'],
    ];
}
