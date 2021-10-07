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
class SystemPermissionValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'name' => 'string|length:0,20',
        'slug' => 'string|length:0,50',
        'url' => 'string|length:0,255',
        'icon' => 'string|length:0,255',
        'component' => 'string|length:0,255',
        'is_external_link' => 'integer',
        'is_display' => 'integer',
        'type' => 'integer',
        'order' => 'integer',
        'status' => 'integer|in:0,1',
        'parent_id' => 'integer',
        'remark' => 'string|length:0,255',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'permission_id' => '',
        'name' => '菜单名称',
        'slug' => '权限标识',
        'url' => 'url地址',
        'status' => '状态',
        'icon' => '图标',
        'component' => '路由地址',
        'is_external_link' => '是否外链',
        'is_display' => '是否显示',
        'type' => '类型：0目录;1菜单;按钮',
        'order' => '排序',
        'parent_id' => '父级ID',
        'create_user_id' => '创建人ID',
        'remark' => '权限备注',
    ];

    protected $scene = [
        'create' => [
            'name',
            'slug',
            'url',
            'icon',
            'status',
            'component',
            'is_external_link',
            'is_display',
            'type',
            'order',
            'parent_id',
            'create_user_id',
            'remark',
        ],
        'update' => [
            'name',
            'slug',
            'url',
            'icon',
            'component',
            'is_external_link',
            'is_display',
            'type',
            'order',
            'parent_id',
            'create_user_id',
            'remark',
        ],
        'delete' => 'permission_id',
        'list' => ['limit', 'page'],
    ];
}
