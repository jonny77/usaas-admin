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
class SystemRateLimiterValidate extends Validate
{
    protected $rule = [
        'limit' => 'integer',
        'page' => 'integer',
        'id' => '',
        'count' => 'integer',
        'ttl' => 'integer',
        'request_uri' => 'string|length:0,255',
        'request_method' => 'string|length:0,50',
        'limit_start_time' => 'string|length:0,50',
        'limit_end_time' => 'string|length:0,50',
        'order' => '',
        'status' => '',
        'remark' => 'max:500',
        'created_at' => 'dateFormat',
        'updated_at' => 'dateFormat',
    ];

    protected $field = [
        'limit' => '条数限制',
        'page' => '分页ID',
        'id' => '',
        'count' => 'QPS',
        'ttl' => '峰值QPS',
        'request_uri' => '请求路由',
        'request_method' => '请求方法',
        'limit_start_time' => '限制开启',
        'limit_end_time' => '限制结束',
        'order' => '排序',
        'status' => '0无效 1 有效',
        'remark' => '扩展参数',
        'created_at' => '',
        'updated_at' => '',
    ];

    protected $scene = [
        'create' => [
            'id',
            'count',
            'ttl',
            'request_uri',
            'request_method',
            'limit_start_time',
            'limit_end_time',
            'order',
            'status',
            'remark',
            'created_at',
            'updated_at',
        ],
        'update' => [
            'id',
            'count',
            'ttl',
            'request_uri',
            'request_method',
            'limit_start_time',
            'limit_end_time',
            'order',
            'status',
            'remark',
            'created_at',
            'updated_at',
        ],
        'delete' => 'id',
        'list' => ['limit', 'page'],
    ];
}
