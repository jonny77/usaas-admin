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

use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string $name 配置名称
 * @property string $key 配置键名
 * @property int $parent_id 父级
 * @property int $order 排序
 * @property string $value 值
 * @property string $type 值类型
 * @property string $component 组件
 * @property int $status 0无效 1 有效
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemConfig extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'field', 'field_type', 'icon', 'key', 'options', 'parent_id', 'required', 'type', 'order', 'value', 'component', 'status', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'status' => 'integer', 'required' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', 'id')->with(['children'])->orderBy('order');
    }
}
