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

use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Utils\Collection;
use UU\Admin\Kernel\Utils\DataExtend;

/**
 * @property int $permission_id
 * @property string $name 权限名
 * @property string $slug 权限标识
 * @property string $url url地址
 * @property string $icon 图标
 * @property string $component 显示组件
 * @property int $is_external_link 是否外链
 * @property int $is_display 是否显示
 * @property int $type 类型：0目录;1菜单;按钮
 * @property int $status 状态
 * @property int $keepalive 是否缓存 0禁用 1 启用
 * @property int $order 排序
 * @property int $parent_id 父级ID
 * @property int $create_user_id 创建人ID
 * @property string $remark 权限备注
 * @property int $tenant_id 租户ID
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class SystemPermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_permissions';

    protected $primaryKey = 'permission_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['permission_id', 'name', 'slug', 'url', 'icon', 'component', 'is_external_link', 'is_display', 'type', 'status', 'keepalive', 'order', 'parent_id', 'create_user_id', 'remark', 'tenant_id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['permission_id' => 'integer', 'is_external_link' => 'integer', 'is_display' => 'integer', 'type' => 'integer', 'status' => 'integer', 'keepalive' => 'integer', 'order' => 'integer', 'parent_id' => 'integer', 'create_user_id' => 'integer', 'tenant_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public static function menus()
    {
        #组装前台需要的结构
        #todo 校验权限，只返回自己有权限的数据
        $db = self::query()->orderBy('order');
        if (! is_super_administrator()) {
            $db->whereIn('permission_id', get_admin_info()->permissionIds());
        }
        $list = $db->whereNotIn('type', [2])->where('status', 1)->get()->map(function ($item) {
            return [
                'path' => $item->url,
                'permission_id' => $item->permission_id,
                'parent_id' => $item->parent_id,
                'name' => $item->name,
                'meta' => [
                    'title' => $item->name,
                    'icon' => $item->icon,
                    'ignoreKeepAlive' => true,
                    'hideMenu' => $item->is_display == 0,
                ],
                'component' => $item->component,
            ];
        })->toArray();
        return Collection::make(DataExtend::arr2tree($list, 'permission_id'))->map(static function ($item) {
            return DataExtend::childrenInMenu($item);
        });
    }

    /**
     * @return HasMany
     */
    public function children()
    {
        return $this->hasMany(get_class($this), 'parent_id', 'permission_id')->with(['children'])->orderBy('order');
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(SystemRole::class, 'system_role_permissions', 'permission_id', 'role_id');
    }
}
