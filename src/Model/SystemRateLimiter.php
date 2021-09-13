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

use Hyperf\Database\Model\Events\Saved;

/**
 * @property int $id
 * @property int $count QPS
 * @property int $ttl 峰值QPS
 * @property string $request_uri 请求路由
 * @property string $request_method 请求方法
 * @property string $limit_start_time 限制开启
 * @property string $limit_end_time 限制结束
 * @property int $order 排序
 * @property int $status 0无效 1 有效
 * @property string $remark 扩展参数
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemRateLimiter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_rate_limiter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'count', 'ttl', 'request_uri', 'request_method', 'limit_start_time', 'limit_end_time', 'order', 'status', 'remark', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'count' => 'integer', 'ttl' => 'integer', 'order' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function saved(Saved $event)
    {
        #删除缓存
        cache()->delete('get_rate_limit');
    }
}
