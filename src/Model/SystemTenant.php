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
 * @property int $tenant_id
 * @property string $name 租户名称
 * @property string $domain 域名前缀
 * @property string $username 用户名
 * @property string $mobile 手机号
 * @property int $status 状态 0 禁用 1 启用
 * @property int $data_source_id 数据源ID 关联system_data_source表 默认0:数据库字段隔离
 * @property string $email 邮箱
 * @property string $start_time 开始时间
 * @property string $end_time 结束时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemTenant extends Model
{
    protected $primaryKey = 'tenant_id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_tenants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tenant_id', 'name', 'domain', 'username', 'mobile', 'status', 'data_source_id', 'email', 'start_time', 'end_time', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['tenant_id' => 'integer', 'status' => 'integer', 'data_source_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
