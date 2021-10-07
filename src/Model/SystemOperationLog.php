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
 * @property int $id
 * @property string $path 路径
 * @property int $user_id 操作员id
 * @property string $method 请求方法
 * @property string $ip IP
 * @property string $request 请求数据
 * @property string $result 响应数据
 * @property string $header 请求头信息
 * @property string $runtime 运行时间
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemOperationLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_operation_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'path', 'user_id', 'method', 'ip', 'request', 'result', 'header', 'runtime', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'request' => 'json', 'result' => 'json', 'header' => 'json', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
